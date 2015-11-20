<?php

use Doctrine\ORM\EntityManager;

// Include Composer autoloader
require __DIR__ . '/../vendor/autoload.php';

// Configure the dependency injection container
$injector = new \Auryn\Injector;
$configuration = new \Scheduler\Configuration\ConfigurationSet;
$configuration->apply($injector);

// Get entity manager from container
/** @var EntityManager $entityManager */
$entityManager = $injector->make('\\Doctrine\\ORM\\EntityManager');

//Truncate tables
$connection = $entityManager->getConnection();
$dbPlatform = $connection->getDatabasePlatform();
$schemaManager = $connection->getSchemaManager();

$connection->query('SET FOREIGN_KEY_CHECKS=0');
foreach($schemaManager->listTableNames() as $tableName) {
    $q = $dbPlatform->getTruncateTableSql($tableName);
    $connection->executeUpdate($q);
}
$connection->query('SET FOREIGN_KEY_CHECKS=1');


//Seed objects from fixtures
$objects = \Nelmio\Alice\Fixtures::load(__DIR__.'/fixtures.yml', $entityManager);

