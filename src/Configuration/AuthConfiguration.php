<?php namespace Scheduler\Configuration;

use Auryn\Injector;
use Scheduler\Auth\Adapter;
use Spark\Auth\AdapterInterface;
use Spark\Auth\Credentials\JsonExtractor;
use Spark\Auth\Token\HeaderExtractor;
use Spark\Configuration\ConfigurationInterface;

/**
 * Class AuthConfiguration
 * @package Scheduler\Configuration
 * @author Sam Tape <sctape@gmail.com>
 */
class AuthConfiguration implements ConfigurationInterface
{
    /**
     * Applies a configuration set to a dependency injector.
     *
     * @param Injector $injector
     */
    public function apply(Injector $injector)
    {
        //Specify token parameter from query string
        $injector->define(
            HeaderExtractor::class,
            [':header' => 'Authorization']
        );

        //Use Query Extractor to grab auth token
        $injector->alias(
            \Spark\Auth\Token\ExtractorInterface::class,
            HeaderExtractor::class
        );

        //Use Json Extractor to grab credentials
        $injector->alias(
            \Spark\Auth\Credentials\ExtractorInterface::class,
            JsonExtractor::class
        );

        //Specify custom auth adapter to use
        $injector->alias(
            AdapterInterface::class,
            Adapter::class
        );
    }
}