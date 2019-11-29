<?php
declare(strict_types = 1);

namespace Middlewares\Tests\Factory;

use Middlewares\Utils\FactoryDiscovery;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\UploadedFileFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;
use Sunrise\Http\Message\RequestFactory;
use Sunrise\Http\Message\ResponseFactory;
use Sunrise\Http\ServerRequest\ServerRequestFactory;
use Sunrise\Http\ServerRequest\UploadedFileFactory;
use Sunrise\Stream\StreamFactory;
use Sunrise\Uri\UriFactory;

class FactorySunriseTest extends TestCase
{
    private static $factory;

    public static function setUpBeforeClass(): void
    {
        self::$factory = new FactoryDiscovery(FactoryDiscovery::SUNRISE);
    }

    public static function tearDownBeforeClass(): void
    {
        self::$factory = null;
    }

    public function testRequest()
    {
        $requestFactory = self::$factory->getRequestFactory();

        $this->assertInstanceOf(RequestFactoryInterface::class, $requestFactory);
        $this->assertInstanceOf(RequestFactory::class, $requestFactory);
    }

    public function testResponse()
    {
        $responseFactory = self::$factory->getResponseFactory();

        $this->assertInstanceOf(ResponseFactoryInterface::class, $responseFactory);
        $this->assertInstanceOf(ResponseFactory::class, $responseFactory);
    }

    public function testServerRequest()
    {
        $serverRequestFactory = self::$factory->getServerRequestFactory();

        $this->assertInstanceOf(ServerRequestFactoryInterface::class, $serverRequestFactory);
        $this->assertInstanceOf(ServerRequestFactory::class, $serverRequestFactory);
    }

    public function testStream()
    {
        $streamFactory = self::$factory->getStreamFactory();

        $this->assertInstanceOf(StreamFactoryInterface::class, $streamFactory);
        $this->assertInstanceOf(StreamFactory::class, $streamFactory);
    }

    public function testUri()
    {
        $uriFactory = self::$factory->getUriFactory();

        $this->assertInstanceOf(UriFactoryInterface::class, $uriFactory);
        $this->assertInstanceOf(UriFactory::class, $uriFactory);
    }

    public function testUploadedFile()
    {
        $uploadedFileFactory = self::$factory->getUploadedFileFactory();

        $this->assertInstanceOf(UploadedFileFactoryInterface::class, $uploadedFileFactory);
        $this->assertInstanceOf(UploadedFileFactory::class, $uploadedFileFactory);
    }
}
