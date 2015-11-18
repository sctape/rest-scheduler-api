<?php namespace Scheduler\Shifts\Commands;

use Scheduler\Shifts\Contracts\Shift;
use Scheduler\Shifts\Repository\ShiftRepository;

/**
 * Class UpdateShiftHandler
 * @package Scheduler\Shifts\Commands
 * @author Sam Tape <sctape@gmail.com>
 */
class UpdateShiftHandler
{
    /**
     * @var ShiftRepository
     */
    private $shiftRepository;

    /**
     * UpdateShiftHandler constructor.
     * @param ShiftRepository $shiftRepository
     */
    public function __construct(ShiftRepository $shiftRepository)
    {
        $this->shiftRepository = $shiftRepository;
    }

    /**
     * @param UpdateShift $command
     * @return Shift
     */
    public function handle(UpdateShift $command)
    {
        $shift = $this->shiftRepository->getOneById($command->shift_id);
        $shift->setBreak($command->break);
        $shift->setStartTime($command->start_time);
        $shift->setEndTime($command->end_time);

        return $shift;
    }
}