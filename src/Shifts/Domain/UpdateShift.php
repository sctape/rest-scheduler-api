<?php namespace Scheduler\Shifts\Domain;

use League\Fractal\Manager;
use League\Fractal\Resource\Item;
use League\Tactician\CommandBus;
use Scheduler\Exception\UserNotAuthorized;
use Scheduler\Shifts\Commands\UpdateShift as UpdateShiftCommand;
use Scheduler\Shifts\Transformer\ShiftTransformer;
use Scheduler\Support\Traits\AuthorizeUser;
use Spark\Adr\DomainInterface;
use Spark\Adr\PayloadInterface;
use Spark\Auth\AuthHandler;
use Spark\Auth\Token;

/**
 * Class UpdateShift
 * @package Scheduler\Shifts\Domain
 * @author Sam Tape <sctape@gmail.com>
 */
class UpdateShift implements DomainInterface
{
    use AuthorizeUser;

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
        //Check that user is authorized to edit this resource
        $this->authorizeUser($input[AuthHandler::TOKEN_ATTRIBUTE]->getMetadata('entity'), 'edit', 'shifts');

        $shift = $this->commandBus->handle(new UpdateShiftCommand($input['id'], $input['break'], $input['start_time'], $input['end_time']));
        $shiftItem = new Item($shift, new ShiftTransformer);

        return $this->payload
            ->withStatus(PayloadInterface::OK)
            ->withOutput($this->fractal->createData($shiftItem)->toArray());
    }
}