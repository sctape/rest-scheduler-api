<?php namespace Scheduler\Codeception\Unit;
use Codeception\TestCase\Test;
use Mockery;
use Scheduler\Shifts\Commands\CreateShift;
use Scheduler\Shifts\Commands\CreateShiftHandler;
use Scheduler\Shifts\Contracts\Shift;
use Scheduler\Shifts\Repository\ShiftRepository;
use Scheduler\Users\Contracts\User;
use Scheduler\Users\Repository\UserRepository;

/**
 * Class CreateShiftHandlerTest
 * @package Scheduler\Codeception\Unit
 * @author Sam Tape <sctape@gmail.com>
 */
class CreateShiftHandlerTest extends Test
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
    public function testHandle()
    {
        $command = new CreateShift(1, 2, 15, date_create('now')->format('r'), date_create('+1 hour')->format('r'));

        $manager = mockery::mock(User::class);
        $employee = mockery::mock(User::class);

        $shift = mockery::mock(Shift::class);
        $shift->shouldReceive('setBreak')->once()->with($command->break)->andReturnNull();
        $shift->shouldReceive('setEmployee')->once()->with($employee)->andReturnNull();
        $shift->shouldReceive('setManager')->once()->with($manager)->andReturnNull();
        $shift->shouldReceive('setStartTime')->once()->with($command->start_time)->andReturnNull();
        $shift->shouldReceive('setEndTime')->once()->with($command->end_time)->andReturnNull();

        $shiftRepository = mockery::mock(ShiftRepository::class);
        $shiftRepository->shouldReceive('store')->once()->with($shift)->andReturnNull();

        $userRepository = mockery::mock(UserRepository::class);
        $userRepository->shouldReceive('getOneById')->once()->with($command->employee_id)->andReturn($employee);
        $userRepository->shouldReceive('getOneById')->once()->with($command->manager_id)->andReturn($manager);

        $commandHandler = new CreateShiftHandler($userRepository, $shiftRepository, $shift);
        $this->assertInstanceOf(Shift::class, $commandHandler->handle($command));
    }
}