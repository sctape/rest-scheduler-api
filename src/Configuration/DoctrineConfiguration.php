<?php namespace Scheduler\Configuration;

use Auryn\Injector;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use Spark\Configuration\ConfigurationInterface;

/**
 * Class DoctrineConfiguration
 * @package Scheduler\Configuration
 * @author Sam Tape <sctape@gmail.com>
 */
class DoctrineConfiguration implements ConfigurationInterface
{
    /**
     * Applies a configuration set to a dependency injector.
     *
     * @param Injector $injector
     */
    public function apply(Injector $injector)
    {
        $paths = [
            "src/Users/Entity/",
            "src/Shifts/Entity/"
        ];

        $isDevMode = true;

        // the connection configuration
        $dbParams = array(
            'driver'   => 'pdo_mysql',
            'user'     => 'homestead',
            'password' => 'secret',
            'dbname'   => 'scheduler',
        );

        $config = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode);
        $injector->share(EntityManager::create($dbParams, $config));
    }
}