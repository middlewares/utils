<?php
declare(strict_types = 1);

namespace Middlewares\Utils;

use Middlewares\Utils\Factory\DiactorosFactory;
use Middlewares\Utils\Factory\GuzzleFactory;
use Middlewares\Utils\Factory\NyholmFactory;
use Middlewares\Utils\Factory\SlimFactory;
use Middlewares\Utils\Factory\SunriseFactory;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriFactoryInterface;
use Psr\Http\Message\UriInterface;
use RuntimeException;

/**
 * Simple class to create instances of PSR-7 classes.
 */
abstract class Factory
{

    /**
     * @var array
     */
    private static $factories = [];

    /**
     * @var array
     */
    private static $strategies = [
        DiactorosFactory::class,
        GuzzleFactory::class,
        NyholmFactory::class,
        SlimFactory::class,
        SunriseFactory::class,
    ];

    /**
     * Create the PSR-17 factories or throw an exception
     */
    private static function getFactory(string $type)
    {
        if (!empty(self::$factories[$type])) {
            return self::$factories[$type];
        }

        foreach (self::$strategies as $strategy) {
            if (!$strategy::isInstalled()) {
                continue;
            }

            $factories = $strategy::getFactories();

            if (!isset($factories[$type]) || !\class_exists($factories[$type])) {
                continue;
            }

            return self::$factories[$type] = new $factories[$type];
        }

        throw new RuntimeException('No PSR-7 library detected');
    }

    /**
     * Change the strategies
     */
    public static function setStrategies(array $strategies = [])
    {
        self::$factories = [];
        self::$strategies = $strategies;
    }

    /**
     * Set a custom ResponseFactory.
     */
    public static function setResponseFactory(ResponseFactoryInterface $responseFactory)
    {
        self::$factories['response'] = $responseFactory;
    }

    /**
     * Get a ResponseFactory.
     */
    public static function getResponseFactory(): ResponseFactoryInterface
    {
        return self::getFactory('response');
    }

    /**
     * Set a custom StreamFactory.
     */
    public static function setStreamFactory(StreamFactoryInterface $streamFactory)
    {
        self::$factories['stream'] = $streamFactory;
    }

    /**
     * Get a StreamFactory.
     */
    public static function getStreamFactory(): StreamFactoryInterface
    {
        return self::getFactory('stream');
    }

    /**
     * Set a custom UriFactory.
     */
    public static function setUriFactory(UriFactoryInterface $uriFactory)
    {
        self::$factories['uri'] = $uriFactory;
    }

    /**
     * Get a UriFactory.
     */
    public static function getUriFactory(): UriFactoryInterface
    {
        return self::getFactory('uri');
    }

    /**
     * Set a custom ServerRequestFactory.
     */
    public static function setServerRequestFactory(ServerRequestFactoryInterface $serverRequestFactory)
    {
        self::$factories['serverRequest'] = $serverRequestFactory;
    }

    /**
     * Get a ServerRequestFactory.
     */
    public static function getServerRequestFactory(): ServerRequestFactoryInterface
    {
        return self::getFactory('serverRequest');
    }

    /**
     * Creates a Response instance.
     */
    public static function createResponse(int $code = 200, string $reasonPhrase = ''): ResponseInterface
    {
        return self::getResponseFactory()->createResponse($code, $reasonPhrase);
    }

    /**
     * Creates a Stream instance.
     */
    public static function createStream(string $content = ''): StreamInterface
    {
        return self::getStreamFactory()->createStream($content);
    }

    /**
     * Creates an Uri instance.
     */
    public static function createUri(string $uri = ''): UriInterface
    {
        return self::getUriFactory()->createUri($uri);
    }

    /**
     * Creates a ServerRequest instance.
     */
    public static function createServerRequest(string $method, $uri, array $serverParams = []): ServerRequestInterface
    {
        return self::getServerRequestFactory()->createServerRequest($method, $uri, $serverParams);
    }
}
