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
use Slim\Http\Headers;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Http\Stream;
use Slim\Http\Uri;

/**
 * Simple class to create response instances of PSR-7 classes.
 */
class SlimFactory implements
    ResponseFactoryInterface,
    ServerRequestFactoryInterface,
    StreamFactoryInterface,
    UriFactoryInterface
{
    /**
     * Check whether Slim is available
     */
    public static function isInstalled(): bool
    {
        return class_exists('Slim\\Http\\Response')
            && class_exists('Slim\\Http\\Request')
            && class_exists('Slim\\Http\\Stream')
            && class_exists('Slim\\Http\\Uri');
    }

    /**
     * @see ResponseFactoryInterface
     */
    public function createResponse(int $code = 200, string $reasonPhrase = ''): ResponseInterface
    {
        $response = new Response($code);

        return $reasonPhrase !== '' ? $response->withStatus($code, $reasonPhrase) : $response;
    }

    /**
     * @see ServerRequestFactoryInterface
     * @param mixed $uri
     */
    public function createServerRequest(string $method, $uri, array $serverParams = []): ServerRequestInterface
    {
        return new Request(
            $method,
            is_string($uri) ? $this->createUri($uri) : $uri,
            new Headers(),
            [],
            $serverParams,
            $this->createStream()
        );
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
        return new Stream($resource);
    }

    /**
     * @see UriFactoryInterface
     */
    public function createUri(string $uri = ''): UriInterface
    {
        return Uri::createFromString($uri);
    }
}
