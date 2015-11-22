<?php namespace Scheduler\Users\Domain;

use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use Scheduler\Exception\UserNotAuthorized;
use Scheduler\Shifts\Repository\ShiftRepository;
use Scheduler\Shifts\Transformer\ShiftTransformer;
use Scheduler\Users\Repository\UserRepository;
use Spark\Adr\DomainInterface;
use Spark\Adr\PayloadInterface;
use Spark\Auth\AuthHandler;

class GetUserShiftCoworkers implements DomainInterface
{
    /**
     * @var PayloadInterface
     */
    private $payload;

    /**
     * @var ShiftRepository
     */
    private $shiftRepository;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var Manager
     */
    private $fractal;

    /**
     * @var \BeatSwitch\Lock\Manager
     */
    private $lockManager;

    /**
     * GetUserShiftCoworkers constructor.
     * @param PayloadInterface $payload
     * @param ShiftRepository $shiftRepository
     * @param UserRepository $userRepository
     * @param Manager $fractal
     * @param \BeatSwitch\Lock\Manager $lockManager
     */
    public function __construct(PayloadInterface $payload, ShiftRepository $shiftRepository, UserRepository $userRepository, Manager $fractal, \BeatSwitch\Lock\Manager $lockManager)
    {
        $this->payload = $payload;
        $this->shiftRepository = $shiftRepository;
        $this->userRepository = $userRepository;
        $this->fractal = $fractal;
        $this->lockManager = $lockManager;
    }


    /**
     * Handle domain logic for an action.
     *
     * @param  array $input
     * @return PayloadInterface
     * @throws UserNotAuthorized
     */
    public function __invoke(array $input)
    {
        //Check that the auth user matches the requested user
        //todo: determine if manager's should have access
        if ($input[AuthHandler::TOKEN_ATTRIBUTE]->getMetadata('id') != $input['id']) {
            throw new UserNotAuthorized;
        }

        $employee = $this->userRepository->getOneByIdOrFail($input['id']);
        $shifts = $this->shiftRepository->getByEmployee($employee);

        foreach($shifts as $shift) {
            $coworkers = $this->userRepository->getEmployeesWorkingBetween($shift->getStartTime(), $shift->getEndTime(), [$employee]);
            $shift->setCoworkers($coworkers);
        }

        $shiftCollection = new Collection($shifts, new ShiftTransformer);

        return $this->payload->withStatus(PayloadInterface::OK)
            ->withOutput($this->fractal->parseIncludes('coworkers')->createData($shiftCollection)->toArray());
    }
}