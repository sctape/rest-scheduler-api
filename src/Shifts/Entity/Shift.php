<?php namespace Scheduler\Shifts\Entity;

use Scheduler\Shifts\Contracts\Shift as ShiftInterface;
use Scheduler\Support\Traits\Timestamps;
use Scheduler\Users\Contracts\User;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Shift
 * @package Scheduler\Shifts\Entity
 * @author Sam Tape <sctape@gmail.com>
 *
 * @ORM\Entity(repositoryClass="Scheduler\Shifts\Repository\ShiftRepository")
 * @ORM\Table(name="shifts")
 * @ORM\HasLifecycleCallbacks
 */
class Shift implements ShiftInterface
{
    use Timestamps;

    /**
     * @var int
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="\Scheduler\Users\Entity\User")
     */
    protected $manager;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="\Scheduler\Users\Entity\User")
     */
    protected $employee;

    /**
     * @var float
     * @ORM\Column(type="float", nullable=true)
     */
    protected $break;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime")
     */
    protected $start_time;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime")
     */
    protected $end_time;

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
    public function setManager(User $manager = null)
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
    public function setEmployee(User $employee = null)
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
     * @param \DateTime|string $start_time
     */
    public function setStartTime($start_time)
    {
        if (! ($start_time instanceof \DateTime)) {
            $start_time = new \DateTime($start_time);
        }

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
     * @param \DateTime|string $end_time
     */
    public function setEndTime($end_time)
    {
        if (! ($end_time instanceof \DateTime)) {
            $end_time = new \DateTime($end_time);
        }

        $this->end_time = $end_time;
    }
}
