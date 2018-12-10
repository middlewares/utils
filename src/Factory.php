<?php
declare(strict_types = 1);

namespace Middlewares\Utils;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriFactoryInterface;
use Psr\Http\Message\UriInterface;

/**
 * Simple class to create instances of PSR-7 classes.
 */
abstract class Factory
{
    private static $instance;

    private static $defaultStrategies = [
        'diactoros' => [
            'serverRequest' => 'Zend\Diactoros\ServerRequestFactory',
            'response' => 'Zend\Diactoros\ResponseFactory',
            'stream' => 'Zend\Diactoros\StreamFactory',
            'uri' => 'Zend\Diactoros\UriFactory',
        ],
        'GuzzleHttp\Psr7\HttpFactory',
        SlimFactory::class,
        'Nyholm\Psr7\Factory\Psr17Factory',
        'sunrise' => [
            'serverRequest' => 'Sunrise\Http\ServerRequest\ServerRequestFactory',
            'response' => 'Sunrise\Http\Message\ResponseFactory',
            'stream' => 'Sunrise\Stream\StreamFactory',
            'uri' => 'Sunrise\Uri\UriFactory',
        ],
    ];

    private static function getInstance(): FactoryDiscovery
    {
        if (!self::$instance) {
            self::$instance = new FactoryDiscovery(self::$defaultStrategies);
        }
        
        return self::$instance;
    }
    
    /**
     * Return the default strategies available
     */
    public static function getDefaultStrategies(): array
    {
        return self::$defaultStrategies;
    }

    /**
     * Change the strategies
     */
    public static function setStrategies(array $strategies)
    {
        self::$instance = new FactoryDiscovery($strategies);
    }

    /**
     * Set a custom ResponseFactory.
     */
    public static function setResponseFactory(ResponseFactoryInterface $responseFactory)
    {
        self::getInstance()->setResponseFactory($responseFactory);
    }

    /**
     * Get a ResponseFactory.
     */
    public static function getResponseFactory(): ResponseFactoryInterface
    {
        return self::getInstance()->getResponseFactory();
    }

    /**
     * Set a custom StreamFactory.
     */
    public static function setStreamFactory(StreamFactoryInterface $streamFactory)
    {
        self::getInstance()->setStreamFactory($streamFactory);
    }

    /**
     * Get a StreamFactory.
     */
    public static function getStreamFactory(): StreamFactoryInterface
    {
        return self::getInstance()->getStreamFactory();
    }

    /**
     * Set a custom UriFactory.
     */
    public static function setUriFactory(UriFactoryInterface $uriFactory)
    {
        self::getInstance()->setUriFactory($uriFactory);
    }

    /**
     * Get a UriFactory.
     */
    public static function getUriFactory(): UriFactoryInterface
    {
        return self::getInstance()->getUriFactory();
    }

    /**
     * Set a custom ServerRequestFactory.
     */
    public static function setServerRequestFactory(ServerRequestFactoryInterface $serverRequestFactory)
    {
        self::getInstance()->setServerRequestFactory($serverRequestFactory);
    }

    /**
     * Get a ServerRequestFactory.
     */
    public static function getServerRequestFactory(): ServerRequestFactoryInterface
    {
        return self::getInstance()->getServerRequestFactory();
    }

    /**
     * Creates a Response instance.
     */
    public static function createResponse(int $code = 200, string $reasonPhrase = ''): ResponseInterface
    {
        return self::getInstance()->createResponse($code, $reasonPhrase);
    }

    /**
     * Creates a Stream instance.
     */
    public static function createStream(string $content = ''): StreamInterface
    {
        return self::getInstance()->createStream($content);
    }

    /**
     * Creates an Uri instance.
     */
    public static function createUri(string $uri = ''): UriInterface
    {
        return self::getInstance()->createUri($uri);
    }

    /**
     * Creates a ServerRequest instance.
     * @param mixed $uri
     */
    public static function createServerRequest(string $method, $uri, array $serverParams = []): ServerRequestInterface
    {
        return self::getInstance()->createServerRequest($method, $uri, $serverParams);
    }
}
