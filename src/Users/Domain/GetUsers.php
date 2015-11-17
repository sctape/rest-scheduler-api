<?php namespace Scheduler\Users\Domain;

use League\Fractal\Manager;
use League\Fractal\Resource\Item;
use Scheduler\Users\Repository\UserRepository;
use Scheduler\Users\Transformer\UserTransformer;
use Spark\Adr\DomainInterface;
use Spark\Adr\PayloadInterface;

/**
 * Class GetUsers
 * @package Scheduler\Users\Domain
 * @author Sam Tape <sctape@gmail.com>
 */
class GetUsers implements DomainInterface
{
    /**
     * @var PayloadInterface
     */
    private $payload;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var UserTransformer
     */
    private $userTransformer;

    /**
     * @var Manager
     */
    private $fractal;

    /**
     * @param PayloadInterface $payload
     * @param UserRepository $userRepository
     * @param UserTransformer $userTransformer
     * @param Manager $fractal
     */
    public function __construct(PayloadInterface $payload, UserRepository $userRepository, UserTransformer $userTransformer, Manager $fractal)
    {
        $this->payload = $payload;
        $this->userRepository = $userRepository;
        $this->userTransformer = $userTransformer;
        $this->fractal = $fractal;
    }

    /**
     * Handle domain logic for an action.
     *
     * @param  array $input
     * @return PayloadInterface
     */
    public function __invoke(array $input)
    {
        $user = $this->userRepository->getOneById($input['id']);
        $resource = new Item($user, $this->userTransformer);

        return $this->payload
            ->withStatus(PayloadInterface::OK)
            ->withOutput($this->fractal->createData($resource)->toArray());
    }
}