<?php

namespace Middlewares\Utils;

use Psr\Http\Message\StreamInterface;
use Psr\Http\Factory\StreamFactoryInterface;

/**
 * Simple class to create instances of PSR-7 streams.
 */
class StreamFactory implements StreamFactoryInterface
{
    /**
     * Creates a Stream instance.
     *
     * @param resource $resource A resource returned by fopen
     *
     * @return StreamInterface
     */
    public function createStream($resource)
    {
        if (class_exists('Zend\\Diactoros\\Stream')) {
            return new \Zend\Diactoros\Stream($resource);
        }

        if (class_exists('GuzzleHttp\\Psr7\\Stream')) {
            return new \GuzzleHttp\Psr7\Stream($resource);
        }

        if (class_exists('Slim\\Http\\Stream')) {
            return new \Slim\Http\Stream($resource);
        }

        throw new \RuntimeException('Unable to create a stream. No PSR-7 stream library detected');
    }
}
