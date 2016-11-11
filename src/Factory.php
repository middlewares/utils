<?php

namespace Middlewares\Utils;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;
use Interop\Http\Factory\ResponseFactoryInterface;
use Interop\Http\Factory\StreamFactoryInterface;
use Interop\Http\Factory\UriFactoryInterface;

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
     * @var UriFactoryInterface
     */
    private static $uriFactory;

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
     * Set a custom uriFactory.
     *
     * @param UriFactoryInterface $uriFactory
     */
    public static function setUriFactory(UriFactoryInterface $uriFactory)
    {
        self::$uriFactory = $uriFactory;
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

    /**
     * Creates an Uri instance.
     *
     * @param string $uri
     *
     * @return UriInterface
     */
    public static function createUri($uri = '')
    {
        if (self::$uriFactory === null) {
            self::$uriFactory = new UriFactory();
        }

        return self::$uriFactory->createUri($uri);
    }
}
