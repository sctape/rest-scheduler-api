<?php namespace Scheduler\Shifts\Commands;

class UpdateShift
{
    /**
     * @var int
     */
    public $shift_id;

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
     * UpdateShift constructor.
     * @param $shift_id
     * @param $break
     * @param $start_time
     * @param $end_time
     */
    public function __construct($shift_id, $break, $start_time, $end_time)
    {
        $this->shift_id = $shift_id;
        $this->break = $break;
        $this->start_time = $start_time;
        $this->end_time = $end_time;
    }
}