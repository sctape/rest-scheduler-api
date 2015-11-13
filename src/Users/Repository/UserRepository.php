<?php namespace Scheduler\Users\Repository;

use Doctrine\ORM\EntityRepository;
use Scheduler\Users\Contracts\User;

/**
 * Class UserRepository
 * @package Scheduler\Users\Repository
 * @author Sam Tape <sctape@gmail.com>
 */
class UserRepository extends EntityRepository
{
    /**
     * @param $id
     * @return User
     */
    public function getOneById($id)
    {
        return $this->find($id);
    }
}