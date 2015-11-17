<?php namespace Scheduler\Shifts\Repository;

use Doctrine\ORM\EntityRepository;
use Scheduler\Shifts\Contracts\Shift;
use Scheduler\Users\Contracts\User;

/**
 * Class ShiftRepository
 * @package Scheduler\Shifts\Repository
 * @author Sam Tape <sctape@gmail.com>
 */
class ShiftRepository extends EntityRepository
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
}