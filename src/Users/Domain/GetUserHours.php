<?php namespace Scheduler\Users\Domain;

use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use Scheduler\Exception\UserNotAuthorized;
use Scheduler\Shifts\Repository\ShiftRepository;
use Scheduler\Shifts\Transformer\HoursTransformer;
use Scheduler\Users\Repository\UserRepository;
use Spark\Adr\DomainInterface;
use Spark\Adr\PayloadInterface;
use Spark\Auth\AuthHandler;

class GetUserHours implements DomainInterface
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
     * @var HoursTransformer
     */
    private $hoursTransformer;

    /**
     * @var Collection
     */
    private $collection;

    /**
     * GetUserHours constructor.
     * @param PayloadInterface $payload
     * @param ShiftRepository $shiftRepository
     * @param UserRepository $userRepository
     * @param Manager $fractal
     * @param HoursTransformer $hoursTransformer
     * @param Collection $collection
     */
    public function __construct(PayloadInterface $payload, ShiftRepository $shiftRepository, UserRepository $userRepository, Manager $fractal, HoursTransformer $hoursTransformer, Collection $collection)
    {
        $this->payload = $payload;
        $this->shiftRepository = $shiftRepository;
        $this->userRepository = $userRepository;
        $this->fractal = $fractal;
        $this->hoursTransformer = $hoursTransformer;
        $this->collection = $collection;
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
        //Make sure requested user matches auth user
        //todo: figure out if managers can access all employees' hours
        if ($input['id'] != $input[AuthHandler::TOKEN_ATTRIBUTE]->getMetaData('id')) {
            throw new UserNotAuthorized;
        }

        //Get hours and transform to more readable collection
        $employee = $this->userRepository->getOneByIdOrFail($input['id']);
        $hours = $this->shiftRepository->getHoursCountGroupedByWeekFor($employee);
        $this->collection->setData($hours)->setTransformer($this->hoursTransformer);

        return $this->payload
            ->withStatus(PayloadInterface::OK)
            ->withOutput($this->fractal->createData($this->collection)->toArray());
    }
}