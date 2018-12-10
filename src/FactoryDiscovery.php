<?php
declare(strict_types = 1);

namespace Middlewares\Utils;

use Middlewares\Utils\Factory\SlimFactory;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriFactoryInterface;
use Psr\Http\Message\UriInterface;
use RuntimeException;

/**
 * Simple class to create instances of PSR-7 classes.
 */
class FactoryDiscovery implements ResponseFactoryInterface, ServerRequestFactoryInterface, StreamFactoryInterface, UriFactoryInterface
{
    private $strategies = [];

    private $factory;
    
    private $factories = [];

    public function __construct(array $strategies)
    {
        $this->strategies = $strategies;
    }

    /**
     * Get the strategies
     */
    public static function getStrategies(): array
    {
        return $this->strategies;
    }

    /**
     * Create the PSR-17 factories or throw an exception
     */
    private function getFactory(string $type)
    {
        if (!empty($this->factories[$type])) {
            return $this->factories[$type];
        }

        if (!empty($this->factory)) {
            return $this->factories[$type] = $this->factory;
        }

        foreach ($this->strategies as $className) {
            if (is_array($className) && isset($className[$type])) {
                $className = $className[$type];

                if (class_exists($className)) {
                    return $this->factories[$type] = new $className();
                }

                continue;
            }

            if (!class_exists($className)) {
                continue;
            }

            if (strpos($className, __NAMESPACE__) === 0 && !$className::isInstalled()) {
                continue;
            }

            return $this->factories[$type] = $this->factory = new $className();
        }

        throw new RuntimeException('No PSR-7 library detected');
    }

    /**
     * Set a custom ResponseFactory.
     */
    public function setResponseFactory(ResponseFactoryInterface $responseFactory)
    {
        $this->factories['response'] = $responseFactory;
    }

    /**
     * Get a ResponseFactory.
     */
    public function getResponseFactory(): ResponseFactoryInterface
    {
        return $this->getFactory('response');
    }

    /**
     * Set a custom StreamFactory.
     */
    public function setStreamFactory(StreamFactoryInterface $streamFactory)
    {
        $this->factories['stream'] = $streamFactory;
    }

    /**
     * Get a StreamFactory.
     */
    public function getStreamFactory(): StreamFactoryInterface
    {
        return $this->getFactory('stream');
    }

    /**
     * Set a custom UriFactory.
     */
    public function setUriFactory(UriFactoryInterface $uriFactory)
    {
        $this->factories['uri'] = $uriFactory;
    }

    /**
     * Get a UriFactory.
     */
    public function getUriFactory(): UriFactoryInterface
    {
        return $this->getFactory('uri');
    }

    /**
     * Set a custom ServerRequestFactory.
     */
    public function setServerRequestFactory(ServerRequestFactoryInterface $serverRequestFactory)
    {
        $this->factories['serverRequest'] = $serverRequestFactory;
    }

    /**
     * Get a ServerRequestFactory.
     */
    public function getServerRequestFactory(): ServerRequestFactoryInterface
    {
        return $this->getFactory('serverRequest');
    }

    /**
     * Creates a Response instance.
     */
    public function createResponse(int $code = 200, string $reasonPhrase = ''): ResponseInterface
    {
        return $this->getResponseFactory()->createResponse($code, $reasonPhrase);
    }

    /**
     * Creates a Stream instance.
     */
    public function createStream(string $content = ''): StreamInterface
    {
        return $this->getStreamFactory()->createStream($content);
    }

    /**
     * Creates a Stream instance from a file.
     */
    public function createStreamFromFile(string $filename, string $mode = 'r'): StreamInterface
    {
        return $this->getStreamFactory()->createStreamFromFile($filename, $mode);
    }

    /**
     * Creates a Stream instance from a resource.
     */
    public function createStreamFromResource($resource): StreamInterface
    {
        return $this->getStreamFactory()->createStreamFromResource($resource);
    }

    /**
     * Creates an Uri instance.
     */
    public function createUri(string $uri = ''): UriInterface
    {
        return $this->getUriFactory()->createUri($uri);
    }

    /**
     * Creates a ServerRequest instance.
     * @param mixed $uri
     */
    public function createServerRequest(string $method, $uri, array $serverParams = []): ServerRequestInterface
    {
        return $this->getServerRequestFactory()->createServerRequest($method, $uri, $serverParams);
    }
}
