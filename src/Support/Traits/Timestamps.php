<?php namespace Scheduler\Support\Traits;

use DateTime;

/**
 * Trait Timestamps
 * @package Scheduler\Support\Traits
 * @author Sam Tape <sctape@gmail.com>
 */
trait Timestamps
{
    /**
     * @Column(name="created_at", type="datetime", nullable=false)
     * @var \DateTime
     */
    protected $created_at;

    /**
     * @Column(name="updated_at", type="datetime", nullable=false)
     * @var \DateTime
     */
    protected $updated_at;

    /**
     * @PrePersist
     */
    public function prePersist()
    {
        $this->created_at = new DateTime;
        $this->updated_at = new DateTime;
    }

    /**
     * @PreUpdate
     */
    public function preUpdate()
    {
        $this->updated_at = new DateTime;
    }

    /**
     *
     * @return DateTime
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * @param DateTime $createdAt
     */
    public function setCreatedAt(DateTime $createdAt)
    {
        $this->created_at = $createdAt;
    }

    /**
     * @return DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    /**
     * @param DateTime $updatedAt
     */
    public function setUpdatedAt(DateTime $updatedAt)
    {
        $this->updated_at = $updatedAt;
    }
}