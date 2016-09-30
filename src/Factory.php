<?php

namespace Middlewares\Utils;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Factory\ResponseFactoryInterface;
use Psr\Http\Factory\StreamFactoryInterface;

/**
 * Simple class to create instances of PSR-7 classes.
 */
abstract class Factory
{
    /**
     * @var ResponseFactoryInterface
     */
    private static $responseFactory;

    /**
     * @var StreamFactoryInterface
     */
    private static $streamFactory;

    /**
     * Set a custom responseFactory.
     *
     * @param ResponseFactoryInterface $responseFactory
     */
    public static function setResponseFactory(ResponseFactoryInterface $responseFactory)
    {
        self::$responseFactory = $responseFactory;
    }

    /**
     * Set a custom streamFactory.
     *
     * @param StreamFactoryInterface $streamFactory
     */
    public static function setStreamFactory(StreamFactoryInterface $streamFactory)
    {
        self::$streamFactory = $streamFactory;
    }

    /**
     * Creates a Response instance.
     *
     * @param int $code The status code
     *
     * @return ResponseInterface
     */
    public static function createResponse($code = 200)
    {
        if (self::$responseFactory === null) {
            self::$responseFactory = new ResponseFactory();
        }

        return self::$responseFactory->createResponse($code);
    }

    /**
     * Creates a Stream instance.
     *
     * @param resource $resource A resource returned by fopen
     *
     * @return StreamInterface
     */
    public static function createStream($resource = null)
    {
        if (self::$streamFactory === null) {
            self::$streamFactory = new StreamFactory();
        }

        if ($resource === null) {
            $resource = fopen('php://temp', 'r+');
        }

        return self::$streamFactory->createStream($resource);
    }
}
