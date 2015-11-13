<?php
/**
 * Created by PhpStorm.
 * User: stape
 * Date: 11/12/15
 * Time: 1:44 PM
 */

namespace Scheduler\Shifts\Entity;

use Scheduler\Users\Entity\User;

/**
 * Class Shift
 * @package Scheduler\Shifts\Entity
 * @author Sam Tape <sctape@gmail.com>
 *
 * @Entity()
 * @Table(name="shifts")
 */
class Shift
{
    /**
     * @var int
     * @Id
     * @GeneratedValue
     * @Column(type="integer")
     */
    protected $id;

    /**
     * @var User
     * @ManyToOne(targetEntity="\Scheduler\Users\Entity\User")
     */
    protected $manager;

    /**
     * @var User
     * @ManyToOne(targetEntity="\Scheduler\Users\Entity\User")
     */
    protected $employee;

    /**
     * @var float
     * @Column(type="float", nullable=true)
     */
    protected $break;

    /**
     * @var \DateTime
     * @Column(type="datetime")
     */
    protected $start_time;

    /**
     * @var \DateTime
     * @Column(type="datetime")
     */
    protected $end_time;

    /**
     * @var \DateTime
     * @Column(type="datetime")
     */
    protected $created_at;

    /**
     * @var \DateTime
     * @Column(type="datetime")
     */
    protected $updated_at;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return User
     */
    public function getManager()
    {
        return $this->manager;
    }

    /**
     * @param User $manager
     */
    public function setManager($manager)
    {
        $this->manager = $manager;
    }

    /**
     * @return mixed
     */
    public function getEmployee()
    {
        return $this->employee;
    }

    /**
     * @param mixed $employee
     */
    public function setEmployee($employee)
    {
        $this->employee = $employee;
    }

    /**
     * @return float
     */
    public function getBreak()
    {
        return $this->break;
    }

    /**
     * @param float $break
     */
    public function setBreak($break)
    {
        $this->break = $break;
    }

    /**
     * @return \DateTime
     */
    public function getStartTime()
    {
        return $this->start_time;
    }

    /**
     * @param \DateTime $start_time
     */
    public function setStartTime($start_time)
    {
        $this->start_time = $start_time;
    }

    /**
     * @return \DateTime
     */
    public function getEndTime()
    {
        return $this->end_time;
    }

    /**
     * @param \DateTime $end_time
     */
    public function setEndTime($end_time)
    {
        $this->end_time = $end_time;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * @param \DateTime $created_at
     */
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    /**
     * @param \DateTime $updated_at
     */
    public function setUpdatedAt($updated_at)
    {
        $this->updated_at = $updated_at;
    }
}
