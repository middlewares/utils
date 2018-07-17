<?php
declare(strict_types = 1);

namespace Middlewares\Utils\Factory;

use Interop\Http\Factory\ServerRequestFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;
use RuntimeException;

/**
 * Simple class to create server request instances of PSR-7 classes.
 */
class ServerRequestFactory implements ServerRequestFactoryInterface
{
    public function createServerRequest(string $method, $uri, array $serverParams = []): ServerRequestInterface
    {
        if (class_exists('Zend\\Diactoros\\ServerRequest')) {
            return new \Zend\Diactoros\ServerRequest(
                $serverParams,
                [],
                $uri,
                $method,
                new \Zend\Diactoros\Stream(fopen('php://temp', 'r+'))
            );
        }

        if (class_exists('GuzzleHttp\\Psr7\\ServerRequest')) {
            return new \GuzzleHttp\Psr7\ServerRequest($method, $uri, [], null, '1.1', $serverParams);
        }

        if (class_exists('Slim\\Http\\Request')) {
            return new \Slim\Http\Request(
                $method,
                is_string($uri) ? \Slim\Http\Uri::createFromString($uri) : $uri,
                new \Slim\Http\Headers(),
                [],
                $serverParams,
                new \Slim\Http\Stream(fopen('php://temp', 'r+'))
            );
        }

        throw new RuntimeException('Unable to create a server request. No PSR-7 library detected');
    }
}
