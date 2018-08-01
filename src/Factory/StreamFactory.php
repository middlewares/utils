<?php
declare(strict_types = 1);

namespace Middlewares\Utils\Factory;

use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\StreamInterface;
use RuntimeException;

/**
 * Simple class to create instances of PSR-7 streams.
 */
class StreamFactory implements StreamFactoryInterface
{
    public function createStream(string $content = ''): StreamInterface
    {
        $stream = $this->createStreamFromFile('php://temp', 'r+');
        $stream->write($content);

        return $stream;
    }

    public function createStreamFromFile(string $filename, string $mode = 'r'): StreamInterface
    {
        return $this->createStreamFromResource(fopen($filename, $mode));
    }

    public function createStreamFromResource($resource): StreamInterface
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

        throw new RuntimeException('Unable to create a stream. No PSR-7 stream library detected');
    }
}
