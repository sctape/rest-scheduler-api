<?php namespace Scheduler\Shifts\Contracts;

use DateTime;
use Scheduler\Users\Contracts\User;

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
    public function setManager(User $manager = null);

    /**
     * @return mixed
     */
    public function getEmployee();

    /**
     * @param mixed $employee
     */
    public function setEmployee(User $employee = null);

    /**
     * @return float
     */
    public function getBreak();

    /**
     * @param float $break
     */
    public function setBreak($break);

    /**
     * @return DateTime
     */
    public function getStartTime();

    /**
     * @param DateTime|string $start_time
     */
    public function setStartTime($start_time);

    /**
     * @return DateTime
     */
    public function getEndTime();

    /**
     * @param DateTime|string $end_time
     */
    public function setEndTime($end_time);

    /**
     * @return DateTime
     */
    public function getCreatedAt();

    /**
     * @param DateTime $created_at
     */
    public function setCreatedAt(DateTime $created_at);

    /**
     * @return DateTime
     */
    public function getUpdatedAt();

    /**
     * @param DateTime $updated_at
     */
    public function setUpdatedAt(DateTime $updated_at);

    /**
     * Get in-memory placeholder for shift coworkers
     *
     * @return array
     */
    public function getCoworkers();

    /**
     * Set in-memory placeholder for shift coworkers
     *
     * @param array $coworkers
     * @return self
     */
    public function setCoworkers(array $coworkers);
}