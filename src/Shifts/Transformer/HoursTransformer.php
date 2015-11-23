<?php namespace Scheduler\Shifts\Transformer;

use League\Fractal\TransformerAbstract;

/**
 * Class HoursTransformer
 * @package Scheduler\Shifts\Transformer
 * @author Sam Tape <sctape@gmail.com>
 */
class HoursTransformer extends TransformerAbstract
{
    /**
     * @param array $hours
     * @return array
     */
    public function transform(array $hours)
    {
        return [
            'week'      => date_create($hours['week_start'])->format('r') . " - " . date_create($hours['week_end'])->format('r'),
            'hours'      => round($hours['hours_count'], 2),
        ];
    }
}