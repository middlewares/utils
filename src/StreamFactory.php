<?php
namespace Middlewares\Utils;

use RuntimeException;
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
     * @return \Psr\Http\Message\StreamInterface
     */
    public function createStream($resource)
    {
        if ($instance = Instances\InstanceFactory::stream($resource)) {
            return $instance;
        }

        throw new RuntimeException('Unable to create a stream. No PSR-7 stream library detected');
    }
}
