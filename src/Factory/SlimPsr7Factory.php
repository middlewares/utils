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
use Slim\Psr7\Factory\ResponseFactory;
use Slim\Psr7\Factory\ServerRequestFactory;
use Slim\Psr7\Factory\StreamFactory;
use Slim\Psr7\Factory\UriFactory;

/**
 * Simple class to create response instances of PSR-7 classes.
 */
class SlimPsr7Factory implements
    ResponseFactoryInterface,
    ServerRequestFactoryInterface,
    StreamFactoryInterface,
    UriFactoryInterface
{
    private static $factories = [];

    /**
     * Check whether Slim 4 PSR-7 is available
     */
    public static function isInstalled(): bool
    {
        return class_exists('Slim\\Psr7\\Factory\\ResponseFactory')
            && class_exists('Slim\\Psr7\\Factory\\ServerRequestFactory')
            && class_exists('Slim\\Psr7\\Factory\\StreamFactory')
            && class_exists('Slim\\Psr7\\Factory\\UriFactory');
    }

    /**
     * @see ResponseFactoryInterface
     */
    public function createResponse(int $code = 200, string $reasonPhrase = ''): ResponseInterface
    {
        return (self::$factories['response'] = self::$factories['response'] ?? new ResponseFactory())
            ->createResponse($code, $reasonPhrase);
    }

    /**
     * @see ServerRequestFactoryInterface
     * @param mixed $uri
     */
    public function createServerRequest(string $method, $uri, array $serverParams = []): ServerRequestInterface
    {
        return (self::$factories['request'] = self::$factories['request'] ?? new ServerRequestFactory())
            ->createServerRequest($method, $uri, $serverParams);
    }

    /**
     * @see StreamFactoryInterface
     */
    public function createStream(string $content = ''): StreamInterface
    {
        $stream = $this->createStreamFromFile('php://temp', 'r+');
        $stream->write($content);

        return $stream;
    }

    /**
     * @see StreamFactoryInterface
     */
    public function createStreamFromFile(string $filename, string $mode = 'r'): StreamInterface
    {
        return $this->createStreamFromResource(fopen($filename, $mode));
    }

    /**
     * @see StreamFactoryInterface
     * @param mixed $resource
     */
    public function createStreamFromResource($resource): StreamInterface
    {
        return (self::$factories['stream'] = self::$factories['stream'] ?? new StreamFactory())
            ->createStreamFromResource($resource);
    }

    /**
     * @see UriFactoryInterface
     */
    public function createUri(string $uri = ''): UriInterface
    {
        return (self::$factories['uri'] = self::$factories['uri'] ?? new UriFactory())
            ->createUri($uri);
    }
}
