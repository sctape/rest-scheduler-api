<?php
/**
 * Created by PhpStorm.
 * User: stape
 * Date: 11/17/15
 * Time: 4:16 PM
 */

namespace Scheduler\Users\Domain;


use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use Scheduler\Shifts\Repository\ShiftRepository;
use Scheduler\Shifts\Transformer\ShiftTransformer;
use Scheduler\Users\Repository\UserRepository;
use Spark\Adr\DomainInterface;
use Spark\Adr\PayloadInterface;

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
     * @param PayloadInterface $payload
     * @param UserRepository $userRepository
     * @param ShiftRepository $shiftRepository
     * @param Manager $fractal
     */
    public function __construct(PayloadInterface $payload, UserRepository $userRepository, ShiftRepository $shiftRepository, Manager $fractal)
    {
        $this->payload = $payload;
        $this->shiftRepository = $shiftRepository;
        $this->userRepository = $userRepository;
        $this->fractal = $fractal;
    }

    public function __invoke(array $input)
    {
        $employee = $this->userRepository->getOneById($input['id']);
        $shifts = $this->shiftRepository->getByEmployee($employee);
        $shiftsCollection = new Collection($shifts, new ShiftTransformer);

        return $this->payload
            ->withStatus(PayloadInterface::OK)
            ->withOutput($this->fractal->createData($shiftsCollection)->toArray());
    }

}