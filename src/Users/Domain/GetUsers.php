<?php namespace Scheduler\Users\Domain;

use League\Fractal\Manager;
use League\Fractal\Resource\Item;
use Scheduler\Users\Repository\UserRepository;
use Scheduler\Users\Transformer\UserTransformer;
use Spark\Adr\DomainInterface;
use Spark\Adr\PayloadInterface;
use Spark\Auth\AuthHandler;
use Spark\Auth\Token;

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
     * @var \BeatSwitch\Lock\Manager
     */
    private $lockManager;

    /**
     * @param PayloadInterface $payload
     * @param UserRepository $userRepository
     * @param UserTransformer $userTransformer
     * @param Manager $fractal
     * @param \BeatSwitch\Lock\Manager $lockManager
     * @internal param ServerRequestInterface $request
     */
    public function __construct(PayloadInterface $payload, UserRepository $userRepository, UserTransformer $userTransformer, Manager $fractal, \BeatSwitch\Lock\Manager $lockManager)
    {
        $this->payload = $payload;
        $this->userRepository = $userRepository;
        $this->userTransformer = $userTransformer;
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
        //Check that user is authorized to view this resource
        if ($this->lockManager->caller($input[AuthHandler::TOKEN_ATTRIBUTE]->getMetadata('entity'))->cannot('view', 'users')) {
            return $this->payload
                ->withStatus(PayloadInterface::INVALID);
        }

        //Get user from repository and transform into resource
        $user = $this->userRepository->getOneById($input['id']);
        $resource = new Item($user, $this->userTransformer);

        return $this->payload
            ->withStatus(PayloadInterface::OK)
            ->withOutput($this->fractal->createData($resource)->toArray());
    }
}