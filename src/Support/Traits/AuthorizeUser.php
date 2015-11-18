<?php namespace Scheduler\Support\Traits;

use BeatSwitch\Lock\Callers\Caller;
use BeatSwitch\Lock\Manager;
use Scheduler\Exception\UserNotAuthorized;

/**
 * Class AuthorizeUser
 * @package Scheduler\Support\Traits
 * @author Sam Tape <sctape@gmail.com>
 */
trait AuthorizeUser
{
    /**
     * @param Caller $user
     * @param $action
     * @param $resource
     * @throws UserNotAuthorized
     */
    public function authorizeUser(Caller $user, $action, $resource)
    {
        if ($this->getLockManager()->caller($user)->cannot($action, $resource)) {
            throw new UserNotAuthorized;
        }
    }

    /**
     * @return Manager
     */
    public function getLockManager()
    {
        return $this->lockManager;
    }
}