<?php namespace Scheduler\Shifts\Commands;

use Scheduler\Shifts\Contracts\Shift;
use Scheduler\Shifts\Repository\ShiftRepository;
use Scheduler\Users\Repository\UserRepository;

/**
 * Class AssignShiftHandler
 * @package Scheduler\Shifts\Commands
 * @author Sam Tape <sctape@gmail.com>
 */
class AssignShiftHandler
{
    /**
     * @var ShiftRepository
     */
    private $shiftRepository;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * AssignShiftHandler constructor.
     * @param ShiftRepository $shiftRepository
     * @param UserRepository $userRepository
     */
    public function __construct(ShiftRepository $shiftRepository, UserRepository $userRepository)
    {
        $this->shiftRepository = $shiftRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * @param AssignShift $command
     * @return Shift
     */
    public function handle(AssignShift $command)
    {
        $shift = $this->shiftRepository->getOneByIdOrFail($command->shift_id);
        $employee = $this->userRepository->getOneByIdOrFail($command->employee_id);

        $shift->setEmployee($employee);

        return $shift;
    }
}