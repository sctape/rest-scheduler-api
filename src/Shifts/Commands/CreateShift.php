<?php namespace Scheduler\Shifts\Commands;

/**
 * Class CreateShift
 * @package Scheduler\Shifts\Commands
 * @author Sam Tape <sctape@gmail.com>
 */
class CreateShift
{
    /**
     * @var int
     */
    public $manager_id;

    /**
     * @var int
     */
    public $employee_id;

    /**
     * @var float
     */
    public $break;

    /**
     * @var string
     */
    public $start_time;

    /**
     * @var string
     */
    public $end_time;

    /**
     * CreateShift constructor.
     * @param $manager_id
     * @param $employee_id
     * @param $break
     * @param $start_time
     * @param $end_time
     */
    public function __construct($manager_id, $employee_id, $break, $start_time, $end_time)
    {
        $this->manager_id = $manager_id;
        $this->employee_id = $employee_id;
        $this->break = $break;
        $this->start_time = $start_time;
        $this->end_time = $end_time;
    }
}