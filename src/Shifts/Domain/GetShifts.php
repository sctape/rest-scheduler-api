<?php namespace Scheduler\Shifts\Domain;

use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use Scheduler\Shifts\Repository\ShiftRepository;
use Scheduler\Shifts\Transformer\ShiftTransformer;
use Scheduler\Support\Traits\AuthorizeUser;
use Spark\Adr\DomainInterface;
use Spark\Adr\PayloadInterface;
use Spark\Auth\AuthHandler;
use Respect\Validation\Validator as v;

/**
 * Class GetShifts
 * @package Scheduler\Shifts\Domain
 * @author Sam Tape <sctape@gmail.com>
 */
class GetShifts implements DomainInterface
{
    use AuthorizeUser;

    /**
     * @var PayloadInterface
     */
    private $payload;

    /**
     * @var ShiftRepository
     */
    private $shiftRepository;

    /**
     * @var Manager
     */
    private $fractal;

    /**
     * @var \BeatSwitch\Lock\Manager
     */
    private $lockManager;

    /**
     * GetShifts constructor.
     * @param PayloadInterface $payload
     * @param ShiftRepository $shiftRepository
     * @param Manager $fractal
     * @param \BeatSwitch\Lock\Manager $lockManager
     */
    public function __construct(PayloadInterface $payload, ShiftRepository $shiftRepository, Manager $fractal, \BeatSwitch\Lock\Manager $lockManager)
    {
        $this->payload = $payload;
        $this->shiftRepository = $shiftRepository;
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
        //Authorize user to be able to view shifts
        $this->authorizeUser($input[AuthHandler::TOKEN_ATTRIBUTE]->getMetaData('entity'), 'view', 'shifts');

        //Validate input
        $inputValidator = v::key('startDateTime', v::stringType())
            ->key('endDateTime', v::stringType());
        $inputValidator->assert($input);

        //Retrieve shifts between in time period
        $shifts = $this->shiftRepository->getShiftsBetween($input['startDateTime'], $input['endDateTime']);
        $shiftsCollection = new Collection($shifts, new ShiftTransformer);

        return $this->payload->withStatus(PayloadInterface::OK)
            ->withOutput($this->fractal->parseIncludes(['manager', 'employee'])->createData($shiftsCollection)->toArray());
    }
}