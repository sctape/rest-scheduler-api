<?php namespace Scheduler\Shifts\Commands;

/**
 * Class AssignShift
 * @package Scheduler\Shifts\Commands
 * @author Sam Tape <sctape@gmail.com>
 */
class AssignShift
{
    /**
     * @var int
     */
    public $shift_id;

    /**
     * @var int
     */
    public $employee_id;

    /**
     * AssignShift constructor.
     */
    public function __construct($shift_id, $employee_id)
    {
        $this->shift_id = $shift_id;
        $this->employee_id = $employee_id;
    }
}