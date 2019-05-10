<?php
declare(strict_types = 1);

namespace Middlewares\Tests\Factory;

use Middlewares\Utils\Factory;
use Middlewares\Utils\FactoryDiscovery;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriFactoryInterface;
use Psr\Http\Message\UriInterface;
use Zend\Diactoros\Response;
use Zend\Diactoros\ResponseFactory;
use Zend\Diactoros\ServerRequest;
use Zend\Diactoros\ServerRequestFactory;
use Zend\Diactoros\Stream;
use Zend\Diactoros\StreamFactory;
use Zend\Diactoros\Uri;
use Zend\Diactoros\UriFactory;

class FactoryDiactorosTest extends TestCase
{
    private static $factory;

    public static function setUpBeforeClass(): void
    {
        self::$factory = new FactoryDiscovery([Factory::DIACTOROS]);
    }

    public static function tearDownBeforeClass(): void
    {
        self::$factory = null;
    }

    public function testResponse()
    {
        $response = self::$factory->createResponse();
        $responseFactory = self::$factory->getResponseFactory();

        $this->assertInstanceOf(ResponseFactoryInterface::class, $responseFactory);
        $this->assertInstanceOf(ResponseFactory::class, $responseFactory);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertInstanceOf(Response::class, $response);
    }

    public function testServerRequest()
    {
        $serverRequest = self::$factory->createServerRequest('GET', '/', []);
        $serverRequestFactory = self::$factory->getServerRequestFactory();

        $this->assertInstanceOf(ServerRequestFactoryInterface::class, $serverRequestFactory);
        $this->assertInstanceOf(ServerRequestFactory::class, $serverRequestFactory);

        $this->assertInstanceOf(ServerRequestInterface::class, $serverRequest);
        $this->assertInstanceOf(ServerRequest::class, $serverRequest);
    }

    public function testStream()
    {
        $stream = self::$factory->createStream();
        $streamFactory = self::$factory->getStreamFactory();

        $this->assertInstanceOf(StreamFactoryInterface::class, $streamFactory);
        $this->assertInstanceOf(StreamFactory::class, $streamFactory);

        $this->assertInstanceOf(StreamInterface::class, $stream);
        $this->assertInstanceOf(Stream::class, $stream);
    }

    public function testUri()
    {
        $uri = self::$factory->createUri();
        $uriFactory = self::$factory->getUriFactory();

        $this->assertInstanceOf(UriFactoryInterface::class, $uriFactory);
        $this->assertInstanceOf(UriFactory::class, $uriFactory);

        $this->assertInstanceOf(UriInterface::class, $uri);
        $this->assertInstanceOf(Uri::class, $uri);
    }
}
