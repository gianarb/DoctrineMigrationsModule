<?php
namespace DoctrineMigrationsModuleTest;

use PHPUnit_Framework_TestCase;
use Zend\ServiceManager\ServiceManager;
use Zend\Mvc\Service\ServiceManagerConfig;
use DoctrineORMModuleTest\Util\ServiceManagerFactory;

class CliTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var \Symfony\Component\Console\Application
     */
    protected $cli;
    /**
     * @var \Doctrine\ORM\EntityManager
a    */
    protected $entityManager;
    /**
     * {@inheritDoc}
     */
    public function setUp()
    {
        $serviceManager     = ServiceManagerFactory::getServiceManager();
        /* @var $sharedEventManager \Zend\EventManager\SharedEventManagerInterface */
        $sharedEventManager = $serviceManager->get('SharedEventManager');
        /* @var $application \Zend\Mvc\Application */
        $application        = $serviceManager->get('Application');
        $invocations        = 0;
        $sharedEventManager->attach(
            'doctrine',
            'loadCli.post',
            function () use (&$invocations) {
                $invocations += 1;
            }
        );
        $application->bootstrap();
        $this->entityManager = $serviceManager->get('doctrine.entitymanager.orm_default');
        $this->cli           = $serviceManager->get('doctrine.cli');
        $this->assertSame(1, $invocations);
    }

    public function testValidCommands()
    {
        $this->assertInstanceOf(
            'Doctrine\DBAL\Migrations\Tools\Console\Command\GenerateCommand',
            $this->cli->get('migrations:generate')
        );
        $this->assertInstanceOf(
            'Doctrine\DBAL\Migrations\Tools\Console\Command\DiffCommand',
            $this->cli->get('migrations:diff')
        );
        $this->assertInstanceOf(
            'Doctrine\DBAL\Migrations\Tools\Console\Command\ExecuteCommand',
            $this->cli->get('migrations:execute')
        );
    }
}
