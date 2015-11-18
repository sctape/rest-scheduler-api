<?php namespace Scheduler\Codeception\Unit\Shifts\Commands;

use Codeception\TestCase\Test;
use Scheduler\Shifts\Commands\CreateShift;

/**
 * Class CreateShiftTest
 * @package Scheduler\Codeception\Unit\Shifts\Commands
 * @author Sam Tape <sctape@gmail.com>
 */
class CreateShiftTest extends Test
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
        $start_time = date_create('now')->format('r');
        $end_time = date_create('+1 hour')->format('r');

        $command = new CreateShift(1, 2, 15, $start_time, $end_time);
        $this->assertEquals(1, $command->manager_id);
        $this->assertEquals(2, $command->employee_id);
        $this->assertEquals(15, $command->break);
        $this->assertEquals($start_time, $command->start_time);
        $this->assertEquals($end_time, $command->end_time);
    }
}