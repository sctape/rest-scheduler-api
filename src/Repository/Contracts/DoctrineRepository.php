<?php namespace Scheduler\Repository\Contracts;
use Doctrine\ORM\EntityNotFoundException;

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

    /**
     * Find an entity by id or throw exception
     *
     * @param $id
     *
     * @return object
     * @throws EntityNotFoundException
     */
    public function getOneByIdOrFail($id);
}