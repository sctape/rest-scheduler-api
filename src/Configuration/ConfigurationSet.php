<?php namespace Scheduler\Configuration;

use Spark\Configuration\DefaultConfigurationSet;

/**
 * Class ConfigurationSet
 * @package Scheduler\Configuration
 * @author Sam Tape <sctape@gmail.com>
 */
class ConfigurationSet extends \Spark\Configuration\ConfigurationSet
{
    public function __construct()
    {
        parent::__construct([
            DefaultConfigurationSet::class,
            DoctrineConfiguration::class,
            DoctrineRepositoryConfiguration::class,
            AliasConfiguration::class,
            AuthConfiguration::class,
            TacticianConfiguration::class,
            AclConfiguration::class,
        ]);
    }
}