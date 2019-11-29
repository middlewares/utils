<?php
declare(strict_types = 1);

namespace Middlewares\Tests\Factory;

use Middlewares\Utils\FactoryDiscovery;
use Nyholm\Psr7\Factory\Psr17Factory;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\UploadedFileFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;

class FactoryNyholmTest extends TestCase
{
    private static $factory;

    public static function setUpBeforeClass(): void
    {
        self::$factory = new FactoryDiscovery(FactoryDiscovery::NYHOLM);
    }

    public static function tearDownBeforeClass(): void
    {
        self::$factory = null;
    }

    public function testRequest()
    {
        $requestFactory = self::$factory->getRequestFactory();

        $this->assertInstanceOf(RequestFactoryInterface::class, $requestFactory);
        $this->assertInstanceOf(Psr17Factory::class, $requestFactory);
    }

    public function testResponse()
    {
        $responseFactory = self::$factory->getResponseFactory();

        $this->assertInstanceOf(ResponseFactoryInterface::class, $responseFactory);
        $this->assertInstanceOf(Psr17Factory::class, $responseFactory);
    }

    public function testServerRequest()
    {
        $serverRequestFactory = self::$factory->getServerRequestFactory();

        $this->assertInstanceOf(ServerRequestFactoryInterface::class, $serverRequestFactory);
        $this->assertInstanceOf(Psr17Factory::class, $serverRequestFactory);
    }

    public function testStream()
    {
        $streamFactory = self::$factory->getStreamFactory();

        $this->assertInstanceOf(StreamFactoryInterface::class, $streamFactory);
        $this->assertInstanceOf(Psr17Factory::class, $streamFactory);
    }

    public function testUri()
    {
        $uriFactory = self::$factory->getUriFactory();

        $this->assertInstanceOf(UriFactoryInterface::class, $uriFactory);
        $this->assertInstanceOf(Psr17Factory::class, $uriFactory);
    }

    public function testUploadedFile()
    {
        $uploadedFileFactory = self::$factory->getUploadedFileFactory();

        $this->assertInstanceOf(UploadedFileFactoryInterface::class, $uploadedFileFactory);
        $this->assertInstanceOf(Psr17Factory::class, $uploadedFileFactory);
    }
}
