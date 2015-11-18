<?php namespace Scheduler\Shifts\Domain;

use League\Fractal\Manager;
use League\Tactician\CommandBus;
use Scheduler\Shifts\Commands\UpdateShift as UpdateShiftCommand;
use Spark\Adr\DomainInterface;
use Spark\Adr\PayloadInterface;
use Spark\Auth\Token;

/**
 * Class UpdateShift
 * @package Scheduler\Shifts\Domain
 * @author Sam Tape <sctape@gmail.com>
 */
class UpdateShift implements DomainInterface
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
     * @var \BeatSwitch\Lock\Manager
     */
    private $lockManager;

    /**
     * UpdateShift constructor.
     * @param PayloadInterface $payload
     * @param CommandBus $commandBus
     * @param Manager $fractal
     * @param \BeatSwitch\Lock\Manager $lockManager
     */
    public function __construct(PayloadInterface $payload, CommandBus $commandBus, Manager $fractal, \BeatSwitch\Lock\Manager $lockManager)
    {
        $this->payload = $payload;
        $this->commandBus = $commandBus;
        $this->fractal = $fractal;
        $this->lockManager = $lockManager;
    }

    /**
     * Handle domain logic for an action.
     *
     * @param  array $input
     * @return PayloadInterface
     */
    public function __invoke(array $input)
    {
        var_dump($input);
        exit;

        $this->commandBus->handle(new UpdateShiftCommand($input['id'], $input['break'], $input['start_time'], $input['end_time']));

        return $this->payload
            ->withStatus(PayloadInterface::OK);
//            ->withOutput($this->fractal->createData($shiftItem)->toArray());
    }
}