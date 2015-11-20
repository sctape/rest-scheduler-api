<?php namespace Scheduler\Shifts\Domain;

use League\Fractal\Manager;
use League\Fractal\Resource\Item;
use League\Tactician\CommandBus;
use Scheduler\Shifts\Commands\AssignShift as AssignShiftCommand;
use Scheduler\Shifts\Transformer\ShiftTransformer;
use Scheduler\Support\Traits\AuthorizeUser;
use Spark\Adr\DomainInterface;
use Spark\Adr\PayloadInterface;
use Spark\Auth\AuthHandler;
use Respect\Validation\Validator as v;

/**
 * Class AssignShift
 * @package Scheduler\Shifts\Domain
 * @author Sam Tape <sctape@gmail.com>
 */
class AssignShift implements DomainInterface
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
     * AssignShift constructor.
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
        //Check that user has permission to edit this resource
        $this->authorizeUser($input[AuthHandler::TOKEN_ATTRIBUTE]->getMetaData('entity'), 'edit', 'shifts');

        //Validate input
        $inputValidator = v::key('id', v::intVal())
            ->key('employee_id', v::intVal());
        $inputValidator->assert($input);

        //Execute command to update employee on shift
        $shift = $this->commandBus->handle(new AssignShiftCommand($input['id'], $input['employee_id']));
        $shiftItem = new Item($shift, new ShiftTransformer);

        return $this->payload->withStatus(PayloadInterface::OK)
            ->withOutput($this->fractal->parseIncludes(['manager','employee'])->createData($shiftItem)->toArray());
    }
}