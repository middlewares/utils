<?php
declare(strict_types=1);

namespace Middlewares\Tests\Factory;

use Middlewares\Utils\Factory;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriFactoryInterface;
use Psr\Http\Message\UriInterface;
use Sunrise\Http\ServerRequest\ServerRequest;
use Sunrise\Http\ServerRequest\ServerRequestFactory;
use Sunrise\Http\Message\Response;
use Sunrise\Http\Message\ResponseFactory;
use Sunrise\Stream\Stream;
use Sunrise\Stream\StreamFactory;
use Sunrise\Uri\Uri;
use Sunrise\Uri\UriFactory;

class FactorySunriseTest extends TestCase
{
    private $originalMiddlewareFactoryStrategies;

    public function setUp()
    {
        $class = new \ReflectionClass(Factory::class);

        $property = $class->getProperty('strategies');
        $property->setAccessible(true);

        $this->originalMiddlewareFactoryStrategies = $property->getValue();
        Factory::setStrategies([$this->originalMiddlewareFactoryStrategies['sunrise']]);
    }

    public function tearDown()
    {
        Factory::setStrategies($this->originalMiddlewareFactoryStrategies);
        $this->originalMiddlewareFactoryStrategies = null;
    }

    public function testResponse()
    {
        $response = Factory::createResponse();
        $responseFactory = Factory::getResponseFactory();

        $this->assertInstanceOf(ResponseFactoryInterface::class, $responseFactory);
        $this->assertInstanceOf(ResponseFactory::class, $responseFactory);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertInstanceOf(Response::class, $response);
    }

    public function testServerRequest()
    {
        $serverRequest = Factory::createServerRequest('GET', '/', []);
        $serverRequestFactory = Factory::getServerRequestFactory();

        $this->assertInstanceOf(ServerRequestFactoryInterface::class, $serverRequestFactory);
        $this->assertInstanceOf(ServerRequestFactory::class, $serverRequestFactory);

        $this->assertInstanceOf(ServerRequestInterface::class, $serverRequest);
        $this->assertInstanceOf(ServerRequest::class, $serverRequest);
    }

    public function testStream()
    {
        $stream = Factory::createStream();
        $streamFactory = Factory::getStreamFactory();

        $this->assertInstanceOf(StreamFactoryInterface::class, $streamFactory);
        $this->assertInstanceOf(StreamFactory::class, $streamFactory);

        $this->assertInstanceOf(StreamInterface::class, $stream);
        $this->assertInstanceOf(Stream::class, $stream);
    }

    public function testUri()
    {
        $uri = Factory::createUri();
        $uriFactory = Factory::getUriFactory();

        $this->assertInstanceOf(UriFactoryInterface::class, $uriFactory);
        $this->assertInstanceOf(UriFactory::class, $uriFactory);

        $this->assertInstanceOf(UriInterface::class, $uri);
        $this->assertInstanceOf(Uri::class, $uri);
    }
}
