<?php namespace Scheduler\Codeception\Unit\Shifts\Commands;

use Codeception\TestCase\Test;
use Mockery;
use Scheduler\Shifts\Commands\UpdateShift;
use Scheduler\Shifts\Commands\UpdateShiftHandler;
use Scheduler\Shifts\Contracts\Shift;
use Scheduler\Shifts\Repository\ShiftRepository;
use Scheduler\Users\Contracts\User;
use Scheduler\Users\Repository\UserRepository;

/**
 * Class UpdateShiftHandlerTest
 * @package Scheduler\Codeception\Unit\Shifts\Commands
 * @author Sam Tape <sctape@gmail.com>
 */
class UpdateShiftHandlerTest extends Test
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
     * Test that the handle method finds the shift and updates the time fields
     */
    public function testHandle()
    {
        $command = new UpdateShift(1, 20, date_create('now')->format('r'), date_create('+1 hour')->format('r'));

        $shift = mockery::mock(Shift::class);
        $shift->shouldReceive('setBreak')->once()->with($command->break)->andReturnNull();
        $shift->shouldReceive('setStartTime')->once()->with($command->start_time)->andReturnNull();
        $shift->shouldReceive('setEndTime')->once()->with($command->end_time)->andReturnNull();

        $shiftRepository = mockery::mock(ShiftRepository::class);
        $shiftRepository->shouldReceive('getOneById')->once()->with(1)->andReturn($shift);

        $commandHandler = new UpdateShiftHandler($shiftRepository);
        $this->assertInstanceOf(Shift::class, $commandHandler->handle($command));
    }
}