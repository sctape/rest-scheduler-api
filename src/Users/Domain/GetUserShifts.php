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
use Respect\Validation\Validator as v;

class GetUserShifts implements DomainInterface
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
     * @var ShiftTransformer
     */
    private $shiftTransformer;

    /**
     * @var Collection
     */
    private $collection;

    /**
     * @param PayloadInterface $payload
     * @param UserRepository $userRepository
     * @param ShiftRepository $shiftRepository
     * @param Manager $fractal
     * @param ShiftTransformer $shiftTransformer
     * @param Collection $collection
     */
    public function __construct(PayloadInterface $payload, UserRepository $userRepository, ShiftRepository $shiftRepository, Manager $fractal, ShiftTransformer $shiftTransformer, Collection $collection)
    {
        $this->payload = $payload;
        $this->shiftRepository = $shiftRepository;
        $this->userRepository = $userRepository;
        $this->fractal = $fractal;
        $this->shiftTransformer = $shiftTransformer;
        $this->collection = $collection;
    }

    /**
     * @param array $input
     * @return PayloadInterface
     * @throws UserNotAuthorized
     */
    public function __invoke(array $input)
    {
        //Don't allow employees to view other employee's shifts
        //todo: figure out if managers can access all employees' shifts
        if ($input['id'] != $input[AuthHandler::TOKEN_ATTRIBUTE]->getMetaData('id')) {
            throw new UserNotAuthorized;
        }

        //Validate input
        $inputValidator = v::key('id', v::intVal());
        $inputValidator->assert($input);

        //Get shifts and transform
        $employee = $this->userRepository->getOneByIdOrFail($input['id']);
        $shifts = $this->shiftRepository->getByEmployee($employee);
        $this->collection->setData($shifts)->setTransformer($this->shiftTransformer);

        $include = array_key_exists('include', $input) ? $input['include'] : '';

        return $this->payload
            ->withStatus(PayloadInterface::OK)
            ->withOutput($this->fractal->parseIncludes($include)->createData($this->collection)->toArray());
    }

}