<?php namespace Scheduler\Configuration;

use Auryn\Injector;
use Scheduler\Shifts\Entity\Shift;
use Scheduler\Shifts\Contracts\Shift as ShiftInterface;
use Scheduler\Users\Entity\User;
use Scheduler\Users\Contracts\User as UserInterface;
use Spark\Configuration\ConfigurationInterface;

/**
 * Class AliasConfiguration
 * @package Scheduler\Configuration
 * @author Sam Tape <sctape@gmail.com>
 */
class AliasConfiguration implements ConfigurationInterface
{
    /**
     * Applies a configuration set to a dependency injector.
     *
     * @param Injector $injector
     */
    public function apply(Injector $injector)
    {
        $injector->alias(ShiftInterface::class, Shift::class);
        $injector->alias(UserInterface::class, User::class);
    }
}