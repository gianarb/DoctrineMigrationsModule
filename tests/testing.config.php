<?php
return array(
    'doctrine' => array(
        'configuration' => array(
            'orm_default' => array(
            ),
        ),
        'migrations_configuration' => array(
            'orm_default' => array(
                'directory' => 'data/DoctrineORMModule/Migrations',
                'name'      => 'Doctrine Database Migrations',
                'namespace' => 'DoctrineORMModule\Migrations',
                'table'     => 'migrations',
            ),
        ),
    ),
);
