<?php namespace Scheduler\Configuration;

use Auryn\Injector;
use Doctrine\Common\Annotations\AnnotationRegistry;
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
            'host'     => $_ENV['DB_HOST'],
            'driver'   => $_ENV['DB_DRIVER'],
            'user'     => $_ENV['DB_USER'],
            'password' => $_ENV['DB_PASSWORD'],
            'dbname'   => $_ENV['DB_DATABASE_NAME'],
        );

        $config = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode, null, null, false);
        $injector->share(EntityManager::create($dbParams, $config));
    }
}