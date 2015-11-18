<?php namespace Scheduler\Codeception\Unit\Exception;

use Codeception\TestCase\Test;
use Scheduler\Exception\UserNotAuthorized;

/**
 * Class UserNotAuthorizedTest
 * @package Scheduler\Codeception\Unit\Exception
 * @author Sam Tape <sctape@gmail.com>
 */
class UserNotAuthorizedTest extends Test
{
    /**
     * Test that the exception returns the correct status code
     */
    public function testGetStatusCode()
    {
        $exception = new UserNotAuthorized;
        $this->assertEquals(401, $exception->getStatusCode());
    }
}