<?php namespace Scheduler\Shifts\Commands;
use Scheduler\Shifts\Entity\Shift;
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
     * CreateShiftHandler constructor.
     * @param UserRepository $userRepository
     * @param ShiftRepository $shiftRepository
     */
    public function __construct(UserRepository $userRepository, ShiftRepository $shiftRepository)
    {
        $this->userRepository = $userRepository;
        $this->shiftRepository = $shiftRepository;
    }

    public function handle(CreateShift $command)
    {
//        var_dump($command);
//        exit;

        $shift = new Shift();
        $shift->setBreak($command->break);
        $shift->setEmployee($this->userRepository->getOneById($command->employee_id));
        $shift->setManager($this->userRepository->getOneById($command->manager_id));
        $shift->setStartTime(new \DateTime($command->start_time));
        $shift->setEndTime(new \DateTime($command->end_time));

        $this->shiftRepository->store($shift);

//        var_dump($shift);
//        exit;
//        $this->shiftRepository->update($shift);

        return $shift;
    }

}