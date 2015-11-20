<?php

// Include Composer autoloader
require __DIR__ . '/../vendor/autoload.php';

// Configure the dependency injection container
$injector = new \Auryn\Injector;
$configuration = new \Scheduler\Configuration\ConfigurationSet;
$configuration->apply($injector);

// Configure middleware
$injector->alias(
    '\\Spark\\Middleware\\Collection',
    '\\Scheduler\\Middleware\\MiddlewareCollection'
);

// Configure the router
$injector->prepare(
    '\\Spark\\Router',
    function(\Spark\Router $router) {
        $router->get('/users/{id}', 'Scheduler\Users\Domain\GetUsers');
        $router->get('/users/{id}/shifts', 'Scheduler\Users\Domain\GetUserShifts');
        $router->get('/users/{id}/hours', 'Scheduler\Users\Domain\GetUserHours');
        $router->post('/shifts/create', 'Scheduler\Shifts\Domain\StoreShift');
        $router->put('/shifts/{id}', 'Scheduler\Shifts\Domain\UpdateShift');
        $router->put('/shifts/{id}/assign/{employee_id}', 'Scheduler\Shifts\Domain\AssignShift');
        $router->get('/shifts', 'Scheduler\Shifts\Domain\GetShifts');
    }
);

// Bootstrap the application
/** @var \Relay\Relay $dispatcher */
$dispatcher = $injector->make('\\Relay\\Relay');
$dispatcher(
    $injector->make('Psr\\Http\\Message\\ServerRequestInterface'),
    $injector->make('Psr\\Http\\Message\\ResponseInterface')
);
