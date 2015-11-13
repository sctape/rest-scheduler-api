<?php namespace Scheduler\Shifts\Contracts;

use Scheduler\Users\Entity\User;

/**
 * Interface Shift
 * @package Scheduler\Shifts\Entity
 * @author Sam Tape <sctape@gmail.com>
 */
interface Shift
{
    /**
     * @return int
     */
    public function getId();

    /**
     * @return User
     */
    public function getManager();

    /**
     * @param User $manager
     */
    public function setManager($manager);

    /**
     * @return mixed
     */
    public function getEmployee();

    /**
     * @param mixed $employee
     */
    public function setEmployee($employee);

    /**
     * @return float
     */
    public function getBreak();

    /**
     * @param float $break
     */
    public function setBreak($break);

    /**
     * @return \DateTime
     */
    public function getStartTime();

    /**
     * @param \DateTime $start_time
     */
    public function setStartTime($start_time);

    /**
     * @return \DateTime
     */
    public function getEndTime();

    /**
     * @param \DateTime $end_time
     */
    public function setEndTime($end_time);

    /**
     * @return \DateTime
     */
    public function getCreatedAt();

    /**
     * @param \DateTime $created_at
     */
    public function setCreatedAt($created_at);

    /**
     * @return \DateTime
     */
    public function getUpdatedAt();

    /**
     * @param \DateTime $updated_at
     */
    public function setUpdatedAt($updated_at);
}