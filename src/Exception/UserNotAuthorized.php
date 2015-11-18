<?php namespace Scheduler\Exception;

/**
 * Class UserNotAuthorized
 * @package Scheduler\Exception
 * @author Sam Tape <sctape@gmail.com>
 */
class UserNotAuthorized extends \Exception
{
    /**
     * Indicate that the response should be 401 Unauthorized
     *
     * @return int
     */
    public function getStatusCode()
    {
        return 401;
    }
}