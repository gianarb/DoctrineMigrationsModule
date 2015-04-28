<?php
namespace DoctrineMigrationsModule;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Zend\ModuleManager\Feature\InitProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\ModuleManagerInterface;
use Zend\Console\Adapter\AdapterInterface as Console;
use Symfony\Component\Console\Input\StringInput;
use DoctrineModule\Component\Console\Output\PropertyOutput;
use Doctrine\DBAL\Version;

class Module implements ConfigProviderInterface, InitProviderInterface
{
    /**
     *  @var $serviceManager  \Zend\ServiceManager\ServiceLocatorInterface
     */
    private $serviceManager;

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
        $this->serviceManager = $event->getParam('ServiceManager');
        $commands = array(
            'doctrine.migrations_cmd.execute',
            'doctrine.migrations_cmd.generate',
            'doctrine.migrations_cmd.migrate',
            'doctrine.migrations_cmd.status',
            'doctrine.migrations_cmd.version',
            'doctrine.migrations_cmd.diff',
            'doctrine.migrations_cmd.latest',
        );
        $cli->addCommands(array_map(array($this->serviceManager, 'get'), $commands));
        $helperSet     = $cli->getHelperSet();
        if(Version::compare("2.5.0") <= 0) {
            $helperSet->set(new \Symfony\Component\Console\Helper\QuestionHelper(), 'dialog');
        } else {
            $helperSet->set(new \Symfony\Component\Console\Helper\DialogHelper(), 'dialog');
        }

    }
    /**
     * {@inheritDoc}
     */
    public function getConfig()
    {
        return include __DIR__ . '/../../config/module.config.php';
    }
}
