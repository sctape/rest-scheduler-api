<?php namespace Scheduler\Shifts\Repository;

use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\EntityRepository;
use Scheduler\Repository\Contracts\DoctrineRepository;
use Scheduler\Shifts\Contracts\Shift;
use Scheduler\Users\Contracts\User;

/**
 * Class ShiftRepository
 * @package Scheduler\Shifts\Repository
 * @author Sam Tape <sctape@gmail.com>
 */
class ShiftRepository extends EntityRepository implements DoctrineRepository
{
    /**
     * @param $id
     * @return Shift
     */
    public function getOneById($id)
    {
        return $this->find($id);
    }

    /**
     * @param User $employee
     * @return Shift[]
     */
    public function getByEmployee(User $employee)
    {
        return $this->findByEmployee($employee);
    }

    /**
     * Persist entity to the database
     *
     * @param Shift $shift
     * @return void
     */
    public function store($shift)
    {
        $this->_em->persist($shift);
    }

    /**
     * Find an entity by id or throw exception
     *
     * @param $id
     *
     * @return Shift
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