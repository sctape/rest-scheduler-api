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

    /**
     * Get all shifts that occur between the given start and end times
     *
     * @param string $startDateTime
     * @param string $endDateTime
     * @return Shift[]
     */
    public function getShiftsBetween($startDateTime, $endDateTime)
    {
        $qb = $this->createQueryBuilder('s')
            ->addSelect('m, e')
            ->leftJoin('s.manager', 'm')
            ->leftJoin('s.employee', 'e')
            ->andWhere('s.start_time < :endDateTime')
            ->andWhere('s.end_time > :startDateTime')
            ->orderBy('s.start_time')
            ->setParameters([
                'startDateTime' => date_create($startDateTime),
                'endDateTime' => date_create($endDateTime)
            ]);

        return $qb->getQuery()->getResult();
    }

    /**
     * @param User $employee
     * @return array
     */
    public function getHoursCountGroupedByWeekFor(User $employee)
    {
        $dbal = $this->_em->getConnection();

        return $dbal->executeQuery("SELECT
  YEARWEEK(start_time) as year_week,
  (SELECT STR_TO_DATE(CONCAT(year_week, ' Sunday'), '%X%V %W')) as week_start,
  (SELECT STR_TO_DATE(CONCAT(year_week, ' Saturday'), '%X%V %W')) as week_end,
  sum(TO_SECONDS(end_time)-TO_SECONDS(start_time))/3600 as hours_count FROM shifts WHERE employee_id = :employee_id GROUP BY year_week;", ['employee_id' => $employee->getId()])->fetchAll();
    }
}