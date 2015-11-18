<?php namespace Scheduler\Codeception\Unit\Shifts\Commands;

use Codeception\TestCase\Test;
use Scheduler\Shifts\Commands\UpdateShift;

/**
 * Class UpdateShiftTest
 * @package Scheduler\Codeception\Unit\Shifts\Commands
 * @author Sam Tape <sctape@gmail.com>
 */
class UpdateShiftTest extends Test
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

    /**
     * Test that the command has the correct fields
     */
    public function testClassHasCorrectAttributes()
    {
        $start_time = date_create('now')->format('r');
        $end_time = date_create('+1 hour')->format('r');

        $command = new UpdateShift(1, 20, $start_time, $end_time);
        $this->assertEquals(1, $command->shift_id);
        $this->assertEquals(20, $command->break);
        $this->assertEquals($start_time, $command->start_time);
        $this->assertEquals($end_time, $command->end_time);
    }
}