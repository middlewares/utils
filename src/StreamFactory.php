<?php

namespace Middlewares\Utils;

use Psr\Http\Message\StreamInterface;
use Interop\Http\Factory\StreamFactoryInterface;

/**
 * Simple class to create instances of PSR-7 streams.
 */
class StreamFactory implements StreamFactoryInterface
{
    /**
     * Create a new stream from a string.
     *
     * The stream SHOULD be created with a temporary resource.
     *
     * @param string $content
     *
     * @return StreamInterface
     */
    public function createStream($content = '')
    {
        $stream = $this->createStreamFromFile('php://temp', 'r+');
        $stream->write($content);

        return $stream;
    }

    /**
     * Create a stream from an existing file.
     *
     * The file MUST be opened using the given mode, which may be any mode
     * supported by the `fopen` function.
     *
     * @param string $file
     * @param string $mode
     *
     * @return StreamInterface
     */
    public function createStreamFromFile($file, $mode = 'r')
    {
        return $this->createStreamFromResource(fopen($file, $mode));
    }

    /**
     * Create a new stream from an existing resource.
     *
     * The stream MUST be readable and may be writable.
     *
     * @param resource $resource
     *
     * @return StreamInterface
     */
    public function createStreamFromResource($resource)
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
