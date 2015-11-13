<?php namespace Scheduler\Users\Domain;

use Scheduler\Users\Repository\UserRepository;
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
     * @param PayloadInterface $payload
     * @param UserRepository $userRepository
     */
    public function __construct(PayloadInterface $payload, UserRepository $userRepository)
    {
        $this->payload = $payload;
        $this->userRepository = $userRepository;
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

        return $this->payload
            ->withStatus(PayloadInterface::OK)
            ->withOutput(['name' => $user->getName()]);
    }
}