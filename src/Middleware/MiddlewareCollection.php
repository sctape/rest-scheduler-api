<?php namespace Scheduler\Middleware;

use Spark\Auth\AuthHandler;
use Spark\Middleware\Collection;
use Spark\Middleware\DefaultCollection;

/**
 * Class MiddlewareCollection
 * @package Scheduler\Middleware
 * @author Sam Tape <sctape@fisdap.net>
 */
class MiddlewareCollection extends Collection
{
    /**
     * @param DefaultCollection $defaults
     */
    public function __construct(DefaultCollection $defaults)
    {
        $middlewares = array_merge($defaults->getArrayCopy(), [
            AuthHandler::class
        ]);
        parent::__construct($middlewares);
    }

}