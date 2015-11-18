<?php namespace Scheduler\Repository\Contracts;

/**
 * Interface DoctrineRepository
 * @package Scheduler\Repository\Contracts
 * @author Sam Tape <sctape@gmail.com>
 */
interface DoctrineRepository
{
    /**
     * Persist entity to the database
     *
     * @param object $entity
     * @return void
     */
    public function store($entity);
}