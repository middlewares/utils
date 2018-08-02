<?php
declare(strict_types = 1);

namespace Middlewares\Utils\Traits;

use Middlewares\Utils\Factory;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\StreamInterface;

/**
 * Simple class to create instances of PSR-7 classes.
 */
trait HasStreamFactory
{
    /**
     * @var StreamFactoryInterface
     */
    private $streamFactory;

    /**
     * Set the stream factory used.
     */
    public function streamFactory(StreamFactoryInterface $streamFactory): self
    {
        $this->streamFactory = $streamFactory;

        return $this;
    }

    private function createStream(string $content = ''): StreamInterface
    {
        $streamFactory = $this->streamFactory ?: Factory::getStreamFactory();

        return $streamFactory->createStream($content);
    }

    private function createStreamFromFile(string $filename, string $mode = 'r'): StreamInterface
    {
        $streamFactory = $this->streamFactory ?: Factory::getStreamFactory();

        return $streamFactory->createStreamFromFile($filename, $mode);
    }

    private function createStreamFromResource($resource): StreamInterface
    {
        $streamFactory = $this->streamFactory ?: Factory::getStreamFactory();

        return $streamFactory->createStreamFromResource($resource);
    }
}
