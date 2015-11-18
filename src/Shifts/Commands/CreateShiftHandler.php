<?php namespace Scheduler\Shifts\Commands;

use Scheduler\Shifts\Contracts\Shift;
use Scheduler\Shifts\Repository\ShiftRepository;
use Scheduler\Users\Repository\UserRepository;

/**
 * Class CreateShiftHandler
 * @package Scheduler\Shifts\Commands
 * @author Sam Tape <sctape@gmail.com>
 */
class CreateShiftHandler
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var ShiftRepository
     */
    private $shiftRepository;

    /**
     * @var Shift
     */
    private $shift;

    /**
     * CreateShiftHandler constructor.
     * @param UserRepository $userRepository
     * @param ShiftRepository $shiftRepository
     * @param Shift $shift
     */
    public function __construct(UserRepository $userRepository, ShiftRepository $shiftRepository, Shift $shift)
    {
        $this->userRepository = $userRepository;
        $this->shiftRepository = $shiftRepository;
        $this->shift = $shift;
    }

    public function handle(CreateShift $command)
    {
        $this->shift->setBreak($command->break);
        $this->shift->setEmployee($this->userRepository->getOneById($command->employee_id));
        $this->shift->setManager($this->userRepository->getOneById($command->manager_id));
        $this->shift->setStartTime($command->start_time);
        $this->shift->setEndTime($command->end_time);

        $this->shiftRepository->store($this->shift);

        return $this->shift;
    }
}