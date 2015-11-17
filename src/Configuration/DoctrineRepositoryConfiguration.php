<?php namespace Scheduler\Configuration;

use Auryn\Injector;
use Doctrine\ORM\EntityManager;
use Scheduler\Shifts\Entity\Shift;
use Scheduler\Shifts\Repository\ShiftRepository;
use Scheduler\Users\Entity\User;
use Scheduler\Users\Repository\UserRepository;
use Spark\Configuration\ConfigurationInterface;

/**
 * Class DoctrineConfiguration
 * @package Scheduler\Configuration
 * @author Sam Tape <sctape@gmail.com>
 */
class DoctrineRepositoryConfiguration implements ConfigurationInterface
{
    protected static $entityRepositoryMap = [
        User::class => UserRepository::class,
        Shift::class => ShiftRepository::class
    ];

    /**
     * Applies a configuration set to a dependency injector.
     *
     * @param Injector $injector
     */
    public function apply(Injector $injector)
    {
        foreach (self::$entityRepositoryMap as $entityName => $repoClassName) {

            $injector->delegate(
                $repoClassName,
                function () use ($entityName, $injector) {
                    /** @var EntityManager $entityManager */
                    $entityManager = $injector->make(EntityManager::class);
                    return $entityManager->getRepository($entityName);
                }
            );
        }
    }
}