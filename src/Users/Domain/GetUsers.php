<?php namespace Scheduler\Users\Domain;

use Doctrine\ORM\EntityManager;
use Scheduler\Users\Entity\User;
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
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @param PayloadInterface $payload
     * @param EntityManager $entityManager
     */
    public function __construct(PayloadInterface $payload, EntityManager $entityManager)
    {
        $this->payload = $payload;
        $this->entityManager = $entityManager;
    }

    /**
     * Handle domain logic for an action.
     *
     * @param  array $input
     * @return PayloadInterface
     */
    public function __invoke(array $input)
    {
        /** @var User $user */
        $user = $this->entityManager->find(User::class, $input['id']);

        return $this->payload
            ->withStatus(PayloadInterface::OK)
            ->withOutput($user->toArray());
    }
}