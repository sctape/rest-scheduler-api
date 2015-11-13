<?php

use Doctrine\ORM\Tools\Console\ConsoleRunner;

// Include Composer autoloader
require __DIR__ . '/../vendor/autoload.php';

// Configure the dependency injection container
$injector = new \Auryn\Injector;
$configuration = new \Scheduler\Configuration\ConfigurationSet;
$configuration->apply($injector);

// replace with mechanism to retrieve EntityManager in your app
/** @var \Relay\Relay $dispatcher */
$entityManager = $injector->make('\\Doctrine\\ORM\\EntityManager');

return ConsoleRunner::createHelperSet($entityManager);