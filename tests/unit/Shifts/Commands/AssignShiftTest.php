<?php namespace Scheduler\Codeception\Unit\Shifts\Commands;

use Codeception\TestCase\Test;
use Scheduler\Shifts\Commands\AssignShift;

/**
 * Class AssignShiftTest
 * @package Scheduler\Codeception\Unit\Shifts\Commands
 * @author Sam Tape <sctape@gmail.com>
 */
class AssignShiftTest extends Test
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before()
    {
    }

    protected function _after()
    {
    }

    // tests
    public function testClassHasCorrectAttributes()
    {
        $command = new AssignShift(1, 2);
        $this->assertEquals(1, $command->shift_id);
        $this->assertEquals(2, $command->employee_id);
    }
}