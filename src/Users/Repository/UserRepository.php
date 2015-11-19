<?php namespace Scheduler\Users\Repository;

use Doctrine\ORM\EntityNotFoundException;
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

    /**
     * Find an entity by id or throw exception
     *
     * @param $id
     *
     * @return User
     * @throws EntityNotFoundException
     */
    public function getOneByIdOrFail($id)
    {
        $entity = $this->find($id);

        if (!$entity) {
            throw new EntityNotFoundException;
        }

        return $entity;
    }
}