<?php
namespace DoctrineMigrationsModule;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Zend\ModuleManager\Feature\InitProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\ModuleManagerInterface;
use Zend\Console\Adapter\AdapterInterface as Console;
use Symfony\Component\Console\Input\StringInput;

class Module implements ConfigProviderInterface, InitProviderInterface
{
    /**
     * {@inheritDoc}
     */
    public function init(ModuleManagerInterface $moduleManager)
    {
        $events = $moduleManager->getEventManager();
        $events->getSharedManager()->attach('doctrine', 'loadCli.post', array($this, 'initializeConsole'));
    }

    public function initializeConsole($event)
    {
        /* @var $cli \Symfony\Component\Console\Application */
        $cli            = $event->getTarget();
        /* @var $serviceLocator \Zend\ServiceManager\ServiceLocatorInterface */
        $serviceLocator = $event->getParam('ServiceManager');
        $commands = array(
            'doctrine.migrations_cmd.execute',
            'doctrine.migrations_cmd.generate',
            'doctrine.migrations_cmd.migrate',
            'doctrine.migrations_cmd.status',
            'doctrine.migrations_cmd.version',
            'doctrine.migrations_cmd.diff',
            'doctrine.migrations_cmd.latest',
        );
        $cli->addCommands(array_map(array($serviceLocator, 'get'), $commands));
        $helperSet     = $cli->getHelperSet();
        $helperSet->set(new \Symfony\Component\Console\Helper\DialogHelper(), 'dialog');
    }
    /**
     * {@inheritDoc}
     */
    public function getConfig()
    {
        return include __DIR__ . '/../../config/module.config.php';
    }
    /**
     * {@inheritDoc}
     */
    public function getConsoleUsage(Console $console)
    {
        /* @var $cli \Symfony\Component\Console\Application */
        $cli    = $this->serviceManager->get('doctrine.cli');
        $output = new PropertyOutput();
        $cli->run(new StringInput('list'), $output);
        return $output->getMessage();
    }
}
