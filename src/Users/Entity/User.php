<?php namespace Scheduler\Users\Entity;

use BeatSwitch\Lock\Callers\Caller;
use Scheduler\Support\Traits\Timestamps;
use Scheduler\Users\Contracts\User as UserInterface;

/**
 * Class User
 * @package Scheduler\Users\Entity
 * @author Sam Tape <sctape@gmail.com>
 *
 * @Entity(repositoryClass="Scheduler\Users\Repository\UserRepository")
 * @Table(name="users")
 * @HasLifecycleCallbacks
 */
class User implements UserInterface, Caller
{
    use Timestamps;

    /**
     * @var int
     * @Id
     * @GeneratedValue
     * @Column(type="integer")
     */
    protected $id;

    /**
     * @var string
     * @Column(type="string")
     */
    protected $name;

    /**
     * @var string
     * @Column(type="string")
     */
    protected $role;

    /**
     * @var string
     * @Column(type="string")
     */
    protected $email;

    /**
     * @var string
     * @Column(type="string")
     */
    protected $phone;

    /**
     * @var string
     * @Column(type="string")
     */
    protected $token;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @param string $role
     */
    public function setRole($role)
    {
        $this->role = $role;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * The type of caller
     *
     * @return string
     */
    public function getCallerType()
    {
        return 'users';
    }

    /**
     * The unique ID to identify the caller with
     *
     * @return int
     */
    public function getCallerId()
    {
        return $this->getId();
    }

    /**
     * The caller's roles
     *
     * @return array
     */
    public function getCallerRoles()
    {
        return [$this->getRole()];
    }
}