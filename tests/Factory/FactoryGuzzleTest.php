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
    /** @var FactoryDiscovery */
    private static $factory;

    public static function setUpBeforeClass(): void
    {
        self::$factory = new FactoryDiscovery(FactoryDiscovery::GUZZLE);
    }

    public function testRequest(): void
    {
        $requestFactory = self::$factory->getRequestFactory();

        self::assertInstanceOf(RequestFactoryInterface::class, $requestFactory);
        self::assertInstanceOf(HttpFactory::class, $requestFactory);
    }

    public function testResponse(): void
    {
        $responseFactory = self::$factory->getResponseFactory();

        self::assertInstanceOf(ResponseFactoryInterface::class, $responseFactory);
        self::assertInstanceOf(HttpFactory::class, $responseFactory);
    }

    public function testServerRequest(): void
    {
        $serverRequestFactory = self::$factory->getServerRequestFactory();

        self::assertInstanceOf(ServerRequestFactoryInterface::class, $serverRequestFactory);
        self::assertInstanceOf(HttpFactory::class, $serverRequestFactory);
    }

    public function testStream(): void
    {
        $streamFactory = self::$factory->getStreamFactory();

        self::assertInstanceOf(StreamFactoryInterface::class, $streamFactory);
        self::assertInstanceOf(HttpFactory::class, $streamFactory);
    }

    public function testUri(): void
    {
        $uriFactory = self::$factory->getUriFactory();

        self::assertInstanceOf(UriFactoryInterface::class, $uriFactory);
        self::assertInstanceOf(HttpFactory::class, $uriFactory);
    }

    public function testUploadedFile(): void
    {
        $uploadedFileFactory = self::$factory->getUploadedFileFactory();

        self::assertInstanceOf(UploadedFileFactoryInterface::class, $uploadedFileFactory);
        self::assertInstanceOf(HttpFactory::class, $uploadedFileFactory);
    }
}
