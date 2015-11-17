<?php namespace Scheduler\Users\Contracts;

/**
 * Interface User
 * @package Scheduler\Users\Entity
 * @author Sam Tape <sctape@gmail.com>
 */
interface User
{
    /**
     * @return int
     */
    public function getId();

    /**
     * @return string
     */
    public function getName();

    /**
     * @param string $name
     */
    public function setName($name);

    /**
     * @return string
     */
    public function getRole();

    /**
     * @param string $role
     */
    public function setRole($role);

    /**
     * @return string
     */
    public function getEmail();

    /**
     * @param string $email
     */
    public function setEmail($email);

    /**
     * @return string
     */
    public function getPhone();

    /**
     * @param string $phone
     */
    public function setPhone($phone);

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