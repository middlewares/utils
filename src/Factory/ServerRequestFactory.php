<?php

namespace Middlewares\Utils\Factory;

use Interop\Http\Factory\ServerRequestFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Simple class to create server request instances of PSR-7 classes.
 */
class ServerRequestFactory implements ServerRequestFactoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function createServerRequest($method, $uri)
    {
        return self::create([], $method, (string) $uri);
    }

    /**
     * {@inheritdoc}
     */
    public function createServerRequestFromArray(array $server)
    {
        return self::create($server, 'GET', '/');
    }

    /**
     * Create a Server request
     *
     * @param array $server
     * @param string $method
     * @param string $uri
     *
     * @return ServerRequestInterface
     */
    private static function create(array $server, $method, $uri)
    {
        if (class_exists('Zend\\Diactoros\\ServerRequest')) {
            return new \Zend\Diactoros\ServerRequest(
                $server,
                [],
                $uri,
                $method,
                new \Zend\Diactoros\Stream(fopen('php://temp', 'r+'))
            );
        }

        if (class_exists('GuzzleHttp\\Psr7\\ServerRequest')) {
            return new \GuzzleHttp\Psr7\ServerRequest($method, $uri, [], null, '1.1', $server);
        }

        if (class_exists('Slim\\Http\\Request')) {
            return new \Slim\Http\Request(
                $method,
                \Slim\Http\Uri::createFromString($uri),
                new \Slim\Http\Headers(),
                [],
                $server,
                new \Slim\Http\Stream(fopen('php://temp', 'r+'))
            );
        }

        throw new \RuntimeException('Unable to create a server request. No PSR-7 server request library detected');
    }
}
