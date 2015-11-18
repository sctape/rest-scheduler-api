<?php namespace Scheduler\Shifts\Domain;

use League\Fractal\Manager;
use League\Fractal\Resource\Item;
use League\Tactician\CommandBus;
use Scheduler\Shifts\Commands\CreateShift;
use Scheduler\Shifts\Transformer\ShiftTransformer;
use Spark\Adr\DomainInterface;
use Spark\Adr\PayloadInterface;

/**
 * Class StoreShift
 * @package Scheduler\Shifts\Domain
 * @author Sam Tape <sctape@gmail.com>
 */
class StoreShift implements DomainInterface
{
    /**
     * @var PayloadInterface
     */
    private $payload;

    /**
     * @var CommandBus
     */
    private $commandBus;

    /**
     * @var Manager
     */
    private $fractal;

    /**
     * StoreShift constructor.
     * @param PayloadInterface $payload
     * @param CommandBus $commandBus
     * @param Manager $fractal
     */
    public function __construct(PayloadInterface $payload, CommandBus $commandBus, Manager $fractal)
    {
        $this->payload = $payload;
        $this->commandBus = $commandBus;
        $this->fractal = $fractal;
    }

    /**
     * Handle domain logic for an action.
     *
     * @param array $input
     * @return PayloadInterface
     */
    public function __invoke(array $input)
    {
        $shift = $this->commandBus->handle(new CreateShift($input['manager_id'], $input['employee_id'], $input['break'], $input['start_time'], $input['end_time']));
        $shiftItem = new Item($shift, new ShiftTransformer);

        return $this->payload
            ->withStatus(PayloadInterface::OK)
            ->withOutput($this->fractal->createData($shiftItem)->toArray());
    }
}