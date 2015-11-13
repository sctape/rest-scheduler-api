<?php namespace Scheduler\Configuration;

use Auryn\Injector;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;

/**
 * Class DoctrineConfiguration
 * @package Scheduler\Configuration
 * @author Sam Tape <sctape@gmail.com>
 */
class DoctrineConfiguration implements \Spark\Configuration\ConfigurationInterface
{
    /**
     * Applies a configuration set to a dependency injector.
     *
     * @param Injector $injector
     */
    public function apply(Injector $injector)
    {
        $injector->delegate(EntityManager::class, function() {
            $paths = array("src/Users/Entity/");
            $paths = array("src/Shifts/Entity/");
            $isDevMode = true;

            // the connection configuration
            $dbParams = array(
                'driver'   => 'pdo_mysql',
                'user'     => 'homestead',
                'password' => 'secret',
                'dbname'   => 'scheduler',
            );

            $config = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode);
            return EntityManager::create($dbParams, $config);
        });
    }
}