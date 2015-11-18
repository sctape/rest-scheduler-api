<?php namespace Scheduler\Configuration;

use Auryn\Injector;
use BeatSwitch\Lock\Drivers\ArrayDriver;
use BeatSwitch\Lock\Manager;
use Spark\Configuration\ConfigurationInterface;

/**
 * Class AclConfiguration
 * @package Scheduler\Configuration
 * @author Sam Tape <sctape@gmail.com>
 */
class AclConfiguration implements ConfigurationInterface
{
    /**
     * Applies a configuration set to a dependency injector.
     *
     * @param Injector $injector
     */
    public function apply(Injector $injector)
    {
        // Create a new Manager instance.
        $manager = new Manager(new ArrayDriver());

        // Managers can do everything
        $manager->role('manager')->allow('all');
//        $manager->role('employee')->allow('guest', 'read');

        $injector->share($manager);
    }
}