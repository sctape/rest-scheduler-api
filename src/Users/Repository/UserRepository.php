<?php namespace Scheduler\Users\Repository;

use Doctrine\ORM\EntityRepository;
use Scheduler\Repository\Contracts\DoctrineRepository;
use Scheduler\Users\Contracts\User;

/**
 * Class UserRepository
 * @package Scheduler\Users\Repository
 * @author Sam Tape <sctape@gmail.com>
 */
class UserRepository extends EntityRepository implements DoctrineRepository
{
    /**
     * @param int $id
     * @return User
     */
    public function getOneById($id)
    {
        return $this->find($id);
    }

    /**
     * @param string $token
     * @return User
     */
    public function getOneByToken($token)
    {
        return $this->findOneByToken($token);
    }

    /**
     * Persist entity to the database
     *
     * @param User $entity
     * @return void
     */
    public function store($entity)
    {
        $this->_em->persist($entity);
    }
}