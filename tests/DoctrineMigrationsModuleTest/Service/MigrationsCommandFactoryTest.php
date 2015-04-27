<?php

namespace DoctrineORMModuleTest\Service;

use DoctrineMigrationsModule\Service\MigrationsCommandFactory;
use PHPUnit_Framework_TestCase as TestCase;
use DoctrineORMModuleTest\Util\ServiceManagerFactory;

class MigrationsCommandFactoryTest extends TestCase
{
    /**
     * @var \Zend\ServiceManager\ServiceManager
     */
    private $serviceLocator;

    /**
     * {@inheritDoc}
     */
    public function setUp()
    {
        $this->serviceLocator = ServiceManagerFactory::getServiceManager();
    }

    public function testExecuteFactory()
    {
        $factory = new MigrationsCommandFactory('execute');

        $this->assertInstanceOf(
            'Doctrine\DBAL\Migrations\Tools\Console\Command\ExecuteCommand',
            $factory->createService($this->serviceLocator)
        );
    }

    public function testDiffFactory()
    {
        $factory = new MigrationsCommandFactory('diff');

        $this->assertInstanceOf(
            'Doctrine\DBAL\Migrations\Tools\Console\Command\DiffCommand',
            $factory->createService($this->serviceLocator)
        );
    }

    public function testThrowException()
    {
        $factory = new MigrationsCommandFactory('unknowncommand');

        $this->setExpectedException('InvalidArgumentException');
        $factory->createService($this->serviceLocator);
    }
}
