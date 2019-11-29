<?php
declare(strict_types = 1);

namespace Middlewares\Tests\Factory;

use GuzzleHttp\Psr7\HttpFactory;
use Middlewares\Utils\FactoryDiscovery;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\UploadedFileFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;

class FactoryGuzzleTest extends TestCase
{
    private static $factory;

    public static function setUpBeforeClass(): void
    {
        self::$factory = new FactoryDiscovery(FactoryDiscovery::GUZZLE);
    }

    public static function tearDownBeforeClass(): void
    {
        self::$factory = null;
    }

    public function testRequest()
    {
        $requestFactory = self::$factory->getRequestFactory();

        $this->assertInstanceOf(RequestFactoryInterface::class, $requestFactory);
        $this->assertInstanceOf(HttpFactory::class, $requestFactory);
    }

    public function testResponse()
    {
        $responseFactory = self::$factory->getResponseFactory();

        $this->assertInstanceOf(ResponseFactoryInterface::class, $responseFactory);
        $this->assertInstanceOf(HttpFactory::class, $responseFactory);
    }

    public function testServerRequest()
    {
        $serverRequestFactory = self::$factory->getServerRequestFactory();

        $this->assertInstanceOf(ServerRequestFactoryInterface::class, $serverRequestFactory);
        $this->assertInstanceOf(HttpFactory::class, $serverRequestFactory);
    }

    public function testStream()
    {
        $streamFactory = self::$factory->getStreamFactory();

        $this->assertInstanceOf(StreamFactoryInterface::class, $streamFactory);
        $this->assertInstanceOf(HttpFactory::class, $streamFactory);
    }

    public function testUri()
    {
        $uriFactory = self::$factory->getUriFactory();

        $this->assertInstanceOf(UriFactoryInterface::class, $uriFactory);
        $this->assertInstanceOf(HttpFactory::class, $uriFactory);
    }

    public function testUploadedFile()
    {
        $uploadedFileFactory = self::$factory->getUploadedFileFactory();

        $this->assertInstanceOf(UploadedFileFactoryInterface::class, $uploadedFileFactory);
        $this->assertInstanceOf(HttpFactory::class, $uploadedFileFactory);
    }
}
