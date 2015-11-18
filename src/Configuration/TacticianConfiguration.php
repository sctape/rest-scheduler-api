<?php namespace Scheduler\Configuration;

use Auryn\Injector;
use Doctrine\ORM\EntityManager;
use League\Tactician\CommandBus;
use League\Tactician\Doctrine\ORM\TransactionMiddleware;
use League\Tactician\Handler\CommandHandlerMiddleware;
use League\Tactician\Handler\CommandNameExtractor\ClassNameExtractor;
use League\Tactician\Handler\Locator\CallableLocator;
use League\Tactician\Handler\MethodNameInflector\HandleInflector;
use League\Tactician\Plugins\LockingMiddleware;
use Scheduler\Shifts\Commands\CreateShift;
use Scheduler\Shifts\Commands\CreateShiftHandler;
use Spark\Configuration\ConfigurationInterface;

/**
 * Class TacticianConfiguration
 * @package Scheduler\Configuration
 * @author Sam Tape <sctape@gmail.com>
 */
class TacticianConfiguration implements ConfigurationInterface
{
    /**
     * @var array mapping of commands to their corresponding handlers
     */
    protected static $commandHandlerMapping = [
        CreateShift::class => CreateShiftHandler::class
    ];

    /**
     * Applies a configuration set to a dependency injector.
     *
     * @param Injector $injector
     */
    public function apply(Injector $injector)
    {
        foreach(self::$commandHandlerMapping as $command => $handler) {
            $injector->alias($command, $handler);
        }

        $injector->delegate(CommandBus::class, function() use ($injector) {
            $handlerMiddleware = new CommandHandlerMiddleware(
                new ClassNameExtractor(),
                new CallableLocator([$injector, 'make']),
                new HandleInflector()
            );

            $lockingMiddleware = new LockingMiddleware();

            $transactionMiddleware = new TransactionMiddleware($injector->make(EntityManager::class));
            return new CommandBus([$transactionMiddleware, $lockingMiddleware, $handlerMiddleware]);
        });
    }
}