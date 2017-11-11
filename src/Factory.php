<?php
declare(strict_types = 1);

namespace Middlewares\Utils;

use Interop\Http\Factory\ResponseFactoryInterface;
use Interop\Http\Factory\ServerRequestFactoryInterface;
use Interop\Http\Factory\StreamFactoryInterface;
use Interop\Http\Factory\UriFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;

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
     * @var ServerRequestFactoryInterface
     */
    private static $serverRequestFactory;

    /**
     * Set a custom ResponseFactory.
     */
    public static function setResponseFactory(ResponseFactoryInterface $responseFactory)
    {
        self::$responseFactory = $responseFactory;
    }

    /**
     * Set a custom StreamFactory.
     */
    public static function setStreamFactory(StreamFactoryInterface $streamFactory)
    {
        self::$streamFactory = $streamFactory;
    }

    /**
     * Set a custom UriFactory.
     */
    public static function setUriFactory(UriFactoryInterface $uriFactory)
    {
        self::$uriFactory = $uriFactory;
    }

    /**
     * Set a custom ServerRequestFactory.
     */
    public static function setServerRequestFactory(ServerRequestFactoryInterface $serverRequestFactory)
    {
        self::$serverRequestFactory = $serverRequestFactory;
    }

    /**
     * Creates a Response instance.
     *
     * @param int $code The status code
     */
    public static function createResponse(int $code = 200): ResponseInterface
    {
        if (self::$responseFactory === null) {
            self::$responseFactory = new Factory\ResponseFactory();
        }

        return self::$responseFactory->createResponse($code);
    }

    /**
     * Creates a Stream instance.
     *
     * @param resource $resource A resource returned by fopen
     */
    public static function createStream($resource = null): StreamInterface
    {
        if (self::$streamFactory === null) {
            self::$streamFactory = new Factory\StreamFactory();
        }

        if ($resource === null) {
            return self::$streamFactory->createStream();
        }

        return self::$streamFactory->createStreamFromResource($resource);
    }

    /**
     * Creates an Uri instance.
     */
    public static function createUri(string $uri = ''): UriInterface
    {
        if (self::$uriFactory === null) {
            self::$uriFactory = new Factory\UriFactory();
        }

        return self::$uriFactory->createUri($uri);
    }

    /**
     * Creates a ServerRequest instance.
     */
    public static function createServerRequest(
        array $server = [],
        string $method = 'GET',
        string $uri = '/'
    ): ServerRequestInterface {
        if (self::$serverRequestFactory === null) {
            self::$serverRequestFactory = new Factory\ServerRequestFactory();
        }

        if (is_string($uri)) {
            $uri = self::createUri($uri);
        }

        return self::$serverRequestFactory->createServerRequestFromArray($server)
            ->withMethod($method)
            ->withUri($uri);
    }
}
