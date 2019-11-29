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
 * Class to create instances of PSR-7 classes.
 */
abstract class Factory
{
    private static $factory;

    public static function getFactory(): FactoryInterface
    {
        if (!self::$factory) {
            static::setFactory(new FactoryDiscovery());
        }

        return self::$factory;
    }

    public static function setFactory(FactoryInterface $factory)
    {
        self::$factory = $factory;
    }

    public static function getResponseFactory(): ResponseFactoryInterface
    {
        return self::getFactory()->getResponseFactory();
    }

    public static function createResponse(int $code = 200, string $reasonPhrase = ''): ResponseInterface
    {
        return self::getResponseFactory()->createResponse($code, $reasonPhrase);
    }

    public static function getServerRequestFactory(): ServerRequestFactoryInterface
    {
        return self::getFactory()->getServerRequestFactory();
    }

    public static function createServerRequest(string $method, $uri, array $serverParams = []): ServerRequestInterface
    {
        return self::getServerRequestFactory()->createServerRequest($method, $uri, $serverParams);
    }

    public static function getStreamFactory(): StreamFactoryInterface
    {
        return self::getFactory()->getStreamFactory();
    }

    public static function createStream(string $content = ''): StreamInterface
    {
        return self::getStreamFactory()->createStream($content);
    }

    public static function getUriFactory(): UriFactoryInterface
    {
        return self::getFactory()->getUriFactory();
    }

    public static function createUri(string $uri = ''): UriInterface
    {
        return self::getUriFactory()->createUri($uri);
    }
}
