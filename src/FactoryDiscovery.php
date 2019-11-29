<?php
declare(strict_types = 1);

namespace Middlewares\Utils;

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
class FactoryDiscovery implements FactoryInterface
{
    const DIACTOROS = [
        'serverRequest' => 'Zend\Diactoros\ServerRequestFactory',
        'response' => 'Zend\Diactoros\ResponseFactory',
        'stream' => 'Zend\Diactoros\StreamFactory',
        'uri' => 'Zend\Diactoros\UriFactory',
    ];
    const GUZZLE = 'GuzzleHttp\Psr7\HttpFactory';
    const SLIM = [
        'serverRequest' => 'Slim\Psr7\Factory\ServerRequestFactory',
        'response' => 'Slim\Psr7\Factory\ResponseFactory',
        'stream' => 'Slim\Psr7\Factory\StreamFactory',
        'uri' => 'Slim\Psr7\Factory\UriFactory',
    ];
    const NYHOLM = 'Nyholm\Psr7\Factory\Psr17Factory';
    const SUNRISE = [
        'serverRequest' => 'Sunrise\Http\ServerRequest\ServerRequestFactory',
        'response' => 'Sunrise\Http\Message\ResponseFactory',
        'stream' => 'Sunrise\Stream\StreamFactory',
        'uri' => 'Sunrise\Uri\UriFactory',
    ];

    private $strategies = [
        self::DIACTOROS,
        self::GUZZLE,
        self::SLIM,
        self::NYHOLM,
        self::SUNRISE,
    ];

    private $factory;

    private $factories = [];

    public function __construct(array $strategies = null)
    {
        if (!empty($strategies)) {
            $this->strategies = $strategies;
        }
    }

    /**
     * Get the strategies
     */
    public function getStrategies(): array
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

            return $this->factories[$type] = $this->factory = new $className();
        }

        throw new RuntimeException('No PSR-7 library detected');
    }

    public function setResponseFactory(ResponseFactoryInterface $responseFactory)
    {
        $this->factories['response'] = $responseFactory;
    }

    public function getResponseFactory(): ResponseFactoryInterface
    {
        return $this->getFactory('response');
    }

    public function setServerRequestFactory(ServerRequestFactoryInterface $serverRequestFactory)
    {
        $this->factories['serverRequest'] = $serverRequestFactory;
    }

    public function getServerRequestFactory(): ServerRequestFactoryInterface
    {
        return $this->getFactory('serverRequest');
    }

    public function setStreamFactory(StreamFactoryInterface $streamFactory)
    {
        $this->factories['stream'] = $streamFactory;
    }

    public function getStreamFactory(): StreamFactoryInterface
    {
        return $this->getFactory('stream');
    }

    public function setUriFactory(UriFactoryInterface $uriFactory)
    {
        $this->factories['uri'] = $uriFactory;
    }

    public function getUriFactory(): UriFactoryInterface
    {
        return $this->getFactory('uri');
    }
}
