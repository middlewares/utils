<?php
declare(strict_types = 1);

namespace Middlewares\Utils\Factory;

use Interop\Http\Factory\UriFactoryInterface;
use Psr\Http\Message\UriInterface;
use RuntimeException;

/**
 * Simple class to create instances of PSR-7 uri.
 */
class UriFactory implements UriFactoryInterface
{
    public function createUri(string $uri = ''): UriInterface
    {
        if (class_exists('Zend\\Diactoros\\Uri')) {
            return new \Zend\Diactoros\Uri($uri);
        }

        if (class_exists('GuzzleHttp\\Psr7\\Uri')) {
            return new \GuzzleHttp\Psr7\Uri($uri);
        }

        if (class_exists('Slim\\Http\\Uri')) {
            return \Slim\Http\Uri::createFromString($uri);
        }

        throw new RuntimeException('Unable to create an uri. No PSR-7 uri library detected');
    }
}
