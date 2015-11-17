<?php namespace Scheduler\Shifts\Transformer;


use League\Fractal\TransformerAbstract;
use Scheduler\Shifts\Contracts\Shift;
use Scheduler\Users\Transformer\UserTransformer;

/**
 * Class ShiftTransformer
 * @package Scheduler\Shifts\Transformer
 * @author Sam Tape <sctape@gmail.com>
 */
class ShiftTransformer extends TransformerAbstract
{
    /**
     * @param Shift $shift
     * @return array
     */
    public function transform(Shift $shift)
    {
        return [
            'id'      => (int) $shift->getId(),
            'manager'   => $this->item($shift->getManager(), new UserTransformer),
            'employee'  => $this->item($shift->getEmployee(), new UserTransformer),
            'break' => $shift->getBreak(),
            'start_time' => $shift->getStartTime()->format('r'),
            'end_time' => $shift->getEndTime()->format('r'),
            'links'   => [
                [
                    'rel' => 'self',
                    'uri' => '/shifts/'.$shift->getId(),
                ]
            ],
        ];
    }
}