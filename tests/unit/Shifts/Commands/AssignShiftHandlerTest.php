<?php namespace Scheduler\Codeception\Unit\Shifts\Commands;

use Codeception\TestCase\Test;
use Mockery;
use Scheduler\Shifts\Commands\AssignShift;
use Scheduler\Shifts\Commands\AssignShiftHandler;
use Scheduler\Shifts\Contracts\Shift;
use Scheduler\Shifts\Repository\ShiftRepository;
use Scheduler\Users\Contracts\User;
use Scheduler\Users\Repository\UserRepository;

/**
 * Class CreateShiftHandlerTest
 * @package Scheduler\Codeception\Unit\Shifts\Commands
 * @author Sam Tape <sctape@gmail.com>
 */
class AssignShiftHandlerTest extends Test
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
        $command = new AssignShift(1, 2);

        $employee = mockery::mock(User::class);

        $shift = mockery::mock(Shift::class);
        $shift->shouldReceive('setEmployee')->once()->with($employee)->andReturnNull();

        $shiftRepository = mockery::mock(ShiftRepository::class);
        $shiftRepository->shouldReceive('getOneByIdOrFail')->once()->with($command->shift_id)->andReturn($shift);

        $userRepository = mockery::mock(UserRepository::class);
        $userRepository->shouldReceive('getOneByIdOrFail')->once()->with($command->employee_id)->andReturn($employee);

        $commandHandler = new AssignShiftHandler($shiftRepository, $userRepository);
        $this->assertInstanceOf(Shift::class, $commandHandler->handle($command));
    }
}