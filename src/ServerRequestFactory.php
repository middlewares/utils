<?php

namespace Middlewares\Utils;

use Interop\Http\Factory\ServerRequestFactoryInterface;

/**
 * Simple class to create server request instances of PSR-7 classes.
 */
class ServerRequestFactory implements ServerRequestFactoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function createServerRequest(array $server, $method = null, $uri = null)
    {
        if (class_exists('Zend\\Diactoros\\ServerRequest')) {
            return new \Zend\Diactoros\ServerRequest($server, [], $uri, $method);
        }

        if (class_exists('GuzzleHttp\\Psr7\\ServerRequest')) {
            return new \GuzzleHttp\Psr7\ServerRequest($method, $uri, [], null, '1.1', $server);
        }

        if (class_exists('Slim\\Http\\Request')) {
            return new \Slim\Http\Request(
                $method,
                \Slim\Http\Uri::createFromString((string) $uri),
                new \Slim\Http\Headers(),
                [],
                $server,
                new \Slim\Http\Stream(fopen('php://temp', 'r+'))
            );
        }

        throw new \RuntimeException('Unable to create a server request. No PSR-7 server request library detected');
    }
}
