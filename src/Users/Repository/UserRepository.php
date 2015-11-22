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

    /**
     * Get all distinct employees working shifts between the given start and end time
     *
     * @param \DateTime $startDateTime
     * @param \DateTime $endDateTime
     * @param array $excludedEmployees
     * @return \Scheduler\Users\Contracts\User[]
     */
    public function getEmployeesWorkingBetween(\DateTime $startDateTime, \DateTime $endDateTime, $excludedEmployees = [])
    {
        $qb = $this->createQueryBuilder('e');
        $qb->select('distinct e')
            ->join('e.employed_shifts', 's')
            ->andWhere('s.start_time < :endDateTime')
            ->andWhere('s.end_time > :startDateTime')
            ->orderBy('s.start_time')
            ->setParameters([
                'startDateTime' => $startDateTime,
                'endDateTime' => $endDateTime
            ]);

        if (! empty($excludedEmployees)) {
            $qb->andWhere('e NOT IN (:excludedEmployees)')
                ->setParameter('excludedEmployees', $excludedEmployees);
        }

        return $qb->getQuery()->getResult();
    }
}