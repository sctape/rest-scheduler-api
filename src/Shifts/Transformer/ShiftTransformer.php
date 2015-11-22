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
     * List of resources to automatically include
     *
     * @var array
     */
    protected $availableIncludes = [
        'employee',
        'manager',
        'coworkers'
    ];

    /**
     * @param Shift $shift
     * @return array
     */
    public function transform(Shift $shift)
    {
        return [
            'id'      => (int) $shift->getId(),
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

    /**
     * Include Employee
     *
     * @param Shift $shift
     * @return \League\Fractal\Resource\Item
     */
    public function includeEmployee(Shift $shift)
    {
        $employee = $shift->getEmployee();

        return $this->item($employee, new UserTransformer);
    }

    /**
     * Include Manager
     *
     * @param Shift $shift
     * @return \League\Fractal\Resource\Item
     */
    public function includeManager(Shift $shift)
    {
        $manager = $shift->getManager();

        return $this->item($manager, new UserTransformer);
    }

    /**
     * Include shift coworkers
     *
     * @param Shift $shift
     * @return \League\Fractal\Resource\Collection
     */
    public function includeCoworkers(Shift $shift)
    {
        $coworkers = $shift->getCoworkers();

        return $this->collection($coworkers, new UserTransformer);
    }
}