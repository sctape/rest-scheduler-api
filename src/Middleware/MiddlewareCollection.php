<?php namespace Scheduler\Middleware;

use Relay\Middleware\ResponseSender;
use Scheduler\Handler\ExceptionHandler;
use Spark\Auth\AuthHandler;
use Spark\Handler\ActionHandler;
use Spark\Handler\FormContentHandler;
use Spark\Handler\JsonContentHandler;
use Spark\Handler\RouteHandler;
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
        $middlewares = [
            AuthHandler::class,
            ResponseSender::class,
            ExceptionHandler::class,
            RouteHandler::class,
            JsonContentHandler::class,
            FormContentHandler::class,
            ActionHandler::class,
        ];

        parent::__construct($middlewares);
    }

}