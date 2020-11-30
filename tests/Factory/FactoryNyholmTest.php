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
    /** @var FactoryDiscovery */
    private static $factory;

    public static function setUpBeforeClass(): void
    {
        self::$factory = new FactoryDiscovery(FactoryDiscovery::NYHOLM);
    }

    public function testRequest(): void
    {
        $requestFactory = self::$factory->getRequestFactory();

        self::assertInstanceOf(RequestFactoryInterface::class, $requestFactory);
        self::assertInstanceOf(Psr17Factory::class, $requestFactory);
    }

    public function testResponse(): void
    {
        $responseFactory = self::$factory->getResponseFactory();

        self::assertInstanceOf(ResponseFactoryInterface::class, $responseFactory);
        self::assertInstanceOf(Psr17Factory::class, $responseFactory);
    }

    public function testServerRequest(): void
    {
        $serverRequestFactory = self::$factory->getServerRequestFactory();

        self::assertInstanceOf(ServerRequestFactoryInterface::class, $serverRequestFactory);
        self::assertInstanceOf(Psr17Factory::class, $serverRequestFactory);
    }

    public function testStream(): void
    {
        $streamFactory = self::$factory->getStreamFactory();

        self::assertInstanceOf(StreamFactoryInterface::class, $streamFactory);
        self::assertInstanceOf(Psr17Factory::class, $streamFactory);
    }

    public function testUri(): void
    {
        $uriFactory = self::$factory->getUriFactory();

        self::assertInstanceOf(UriFactoryInterface::class, $uriFactory);
        self::assertInstanceOf(Psr17Factory::class, $uriFactory);
    }

    public function testUploadedFile(): void
    {
        $uploadedFileFactory = self::$factory->getUploadedFileFactory();

        self::assertInstanceOf(UploadedFileFactoryInterface::class, $uploadedFileFactory);
        self::assertInstanceOf(Psr17Factory::class, $uploadedFileFactory);
    }
}
