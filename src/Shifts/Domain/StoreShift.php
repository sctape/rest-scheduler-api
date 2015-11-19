<?php namespace Scheduler\Shifts\Domain;

use League\Fractal\Manager;
use League\Fractal\Resource\Item;
use League\Tactician\CommandBus;
use Scheduler\Shifts\Commands\CreateShift;
use Scheduler\Shifts\Transformer\ShiftTransformer;
use Scheduler\Support\Traits\AuthorizeUser;
use Spark\Adr\DomainInterface;
use Spark\Adr\PayloadInterface;
use Spark\Auth\AuthHandler;
use Respect\Validation\Validator as v;

/**
 * Class StoreShift
 * @package Scheduler\Shifts\Domain
 * @author Sam Tape <sctape@gmail.com>
 */
class StoreShift implements DomainInterface
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
     * StoreShift constructor.
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
     * @param array $input
     * @return PayloadInterface
     */
    public function __invoke(array $input)
    {
        //Ensure that the use has permission to create shifts
        $user = $input[AuthHandler::TOKEN_ATTRIBUTE]->getMetaData('entity');
        $this->authorizeUser($user, 'create', 'shifts');

        //If no manager_id is specified in request, default to user creating shift
        if (!array_key_exists('manager_id', $input)) {
            $input['manager_id'] = $user->getId();
        }

        //Validate input
        $inputValidator = v::key('break', v::floatVal())
            ->key('start_time', v::stringType())
            ->key('end_time', v::stringType())
            ->key('manager_id', v::intVal());
        $inputValidator->assert($input);

        //Execute command to create shift
        $shift = $this->commandBus->handle(new CreateShift($input['manager_id'], $input['employee_id'], $input['break'], $input['start_time'], $input['end_time']));
        $shiftItem = new Item($shift, new ShiftTransformer);

        return $this->payload
            ->withStatus(PayloadInterface::OK)
            ->withOutput($this->fractal->createData($shiftItem)->toArray());
    }
}