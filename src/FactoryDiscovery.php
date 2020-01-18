<?php
declare(strict_types = 1);

namespace Middlewares\Utils;

use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\UploadedFileFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;
use RuntimeException;

/**
 * Simple class to create instances of PSR-17 classes.
 */
class FactoryDiscovery implements FactoryInterface
{
    const DIACTOROS = [
        'request' => 'Laminas\Diactoros\RequestFactory',
        'response' => 'Laminas\Diactoros\ResponseFactory',
        'serverRequest' => 'Laminas\Diactoros\ServerRequestFactory',
        'stream' => 'Laminas\Diactoros\StreamFactory',
        'uploadedFile' => 'Laminas\Diactoros\UploadedFileFactory',
        'uri' => 'Laminas\Diactoros\UriFactory',
    ];
    const GUZZLE = 'GuzzleHttp\Psr7\HttpFactory';
    const SLIM = [
        'request' => 'Slim\Psr7\Factory\RequestFactory',
        'response' => 'Slim\Psr7\Factory\ResponseFactory',
        'serverRequest' => 'Slim\Psr7\Factory\ServerRequestFactory',
        'stream' => 'Slim\Psr7\Factory\StreamFactory',
        'uploadedFile' => 'Slim\Psr7\Factory\UploadedFileFactory',
        'uri' => 'Slim\Psr7\Factory\UriFactory',
    ];
    const NYHOLM = 'Nyholm\Psr7\Factory\Psr17Factory';
    const SUNRISE = [
        'request' => 'Sunrise\Http\Message\RequestFactory',
        'response' => 'Sunrise\Http\Message\ResponseFactory',
        'serverRequest' => 'Sunrise\Http\ServerRequest\ServerRequestFactory',
        'stream' => 'Sunrise\Stream\StreamFactory',
        'uploadedFile' => 'Sunrise\Http\ServerRequest\UploadedFileFactory',
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

    public function __construct(...$strategies)
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

    public function setRequestFactory(RequestFactoryInterface $requestFactory)
    {
        $this->factories['request'] = $requestFactory;
    }

    public function getRequestFactory(): RequestFactoryInterface
    {
        return $this->getFactory('request');
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

    public function setUploadedFileFactory(UploadedFileFactoryInterface $uploadedFileFactory)
    {
        $this->factories['uploadedFile'] = $uploadedFileFactory;
    }

    public function getUploadedFileFactory(): UploadedFileFactoryInterface
    {
        return $this->getFactory('uploadedFile');
    }

    public function setUriFactory(UriFactoryInterface $uriFactory)
    {
        $this->factories['uri'] = $uriFactory;
    }

    public function getUriFactory(): UriFactoryInterface
    {
        return $this->getFactory('uri');
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

        throw new RuntimeException(sprintf('No PSR-17 factory detected to create a %s', $type));
    }
}
