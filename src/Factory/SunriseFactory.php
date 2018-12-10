<?php
declare(strict_types = 1);

namespace Middlewares\Utils\Factory;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriFactoryInterface;
use Psr\Http\Message\UriInterface;
use Sunrise\Http\Message\ResponseFactory;
use Sunrise\Http\ServerRequest\ServerRequestFactory;
use Sunrise\Stream\StreamFactory;
use Sunrise\Uri\UriFactory;

/**
 * Simple class to create response instances of PSR-7 classes.
 */
class SunriseFactory implements
    ResponseFactoryInterface,
    ServerRequestFactoryInterface,
    StreamFactoryInterface,
    UriFactoryInterface
{

    /**
     * Check whether Sunrise is available
     *
     * @return bool
     */
    public static function isInstalled() : bool
    {
        return class_exists('Sunrise\\Http\\Message\\ResponseFactory')
            && class_exists('Sunrise\\Http\\ServerRequest\\ServerRequestFactory')
            && class_exists('Sunrise\\Stream\\StreamFactory')
            && class_exists('Sunrise\\Uri\\UriFactory');
    }

    /**
     * {@inheritDoc}
     *
     * @see \Sunrise\Http\Message\ResponseFactory
     * @link https://github.com/sunrise-php/http-message
     */
    public function createResponse(int $code = 200, string $reasonPhrase = '') : ResponseInterface
    {
        return (new ResponseFactory)
        ->createResponse($code, $reasonPhrase);
    }

    /**
     * {@inheritDoc}
     *
     * @see \Sunrise\Http\ServerRequest\ServerRequestFactory
     * @link https://github.com/sunrise-php/http-server-request
     */
    public function createServerRequest(string $method, $uri, array $serverParams = []) : ServerRequestInterface
    {
        return (new ServerRequestFactory)
        ->createServerRequest($method, $uri, $serverParams);
    }

    /**
     * {@inheritDoc}
     *
     * @see \Sunrise\Stream\StreamFactory
     * @link https://github.com/sunrise-php/stream
     */
    public function createStream(string $content = '') : StreamInterface
    {
        return (new StreamFactory)
        ->createStream($content);
    }

    /**
     * {@inheritDoc}
     *
     * @see \Sunrise\Stream\StreamFactory
     * @link https://github.com/sunrise-php/stream
     */
    public function createStreamFromFile(string $filename, string $mode = 'r'): StreamInterface
    {
        return (new StreamFactory)
        ->createStreamFromFile($filename, $mode);
    }

    /**
     * {@inheritDoc}
     *
     * @see \Sunrise\Stream\StreamFactory
     * @link https://github.com/sunrise-php/stream
     */
    public function createStreamFromResource($resource): StreamInterface
    {
        return (new StreamFactory)
        ->createStreamFromResource($resource);
    }

    /**
     * {@inheritDoc}
     *
     * @see \Sunrise\Uri\UriFactory
     * @link https://github.com/sunrise-php/uri
     */
    public function createUri(string $uri = ''): UriInterface
    {
        return (new UriFactory)
        ->createUri($uri);
    }
}
