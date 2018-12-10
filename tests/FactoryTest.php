<?php
declare(strict_types = 1);

namespace Middlewares\Tests;

use Middlewares\Utils\Factory\DiactorosFactory;
use Middlewares\Utils\Factory\GuzzleFactory;
use Middlewares\Utils\Factory\NyholmFactory;
use Middlewares\Utils\Factory\SlimFactory;
use Middlewares\Utils\Factory\SunriseFactory;
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

class FactoryTest extends TestCase
{
    public function strategyProvider()
    {
        return [
            [DiactorosFactory::class],
            [GuzzleFactory::class],
            [NyholmFactory::class],
            [SlimFactory::class],
            [SunriseFactory::class],
        ];
    }

    /**
     * @dataProvider strategyProvider
     */
    public function testStrategy(string $strategy)
    {
        Factory::setStrategies([$strategy]);

        $response = Factory::createResponse();
        $responseFactory = Factory::getResponseFactory();
        $this->assertInstanceOf(ResponseFactoryInterface::class, $responseFactory);
        $this->assertInstanceOf(ResponseInterface::class, $response);

        $serverRequest = Factory::createServerRequest('GET', '/', []);
        $serverRequestFactory = Factory::getServerRequestFactory();
        $this->assertInstanceOf(ServerRequestFactoryInterface::class, $serverRequestFactory);
        $this->assertInstanceOf(ServerRequestInterface::class, $serverRequest);

        $stream = Factory::createStream();
        $streamFactory = Factory::getStreamFactory();
        $this->assertInstanceOf(StreamFactoryInterface::class, $streamFactory);
        $this->assertInstanceOf(StreamInterface::class, $stream);

        $uri = Factory::createUri();
        $uriFactory = Factory::getUriFactory();
        $this->assertInstanceOf(UriFactoryInterface::class, $uriFactory);
        $this->assertInstanceOf(UriInterface::class, $uri);

        Factory::setStrategies([
            DiactorosFactory::class,
            GuzzleFactory::class,
            NyholmFactory::class,
            SlimFactory::class,
            SunriseFactory::class,
        ]);
    }
}
