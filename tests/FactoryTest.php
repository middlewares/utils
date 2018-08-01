<?php
declare(strict_types = 1);

namespace Middlewares\Tests;

use Middlewares\Utils\Factory;
use Middlewares\Utils\Factory\DiactorosFactory;
use Middlewares\Utils\Factory\GuzzleFactory;
use Middlewares\Utils\Factory\SlimFactory;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequest;
use Zend\Diactoros\Stream;
use Zend\Diactoros\Uri;

class FactoryTest extends TestCase
{
    public function testResponse()
    {
        $response = Factory::createResponse();
        $responseFactory = Factory::getResponseFactory();

        $this->assertInstanceOf(ResponseFactoryInterface::class, $responseFactory);
        $this->assertInstanceOf(DiactorosFactory::class, $responseFactory);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertInstanceOf(Response::class, $response);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($response->getBody()->isWritable());
        $this->assertTrue($response->getBody()->isSeekable());
    }

    public function testStream()
    {
        $stream = Factory::createStream('Hello world');
        $streamFactory = Factory::getStreamFactory();

        $this->assertInstanceOf(StreamFactoryInterface::class, $streamFactory);
        $this->assertInstanceOf(DiactorosFactory::class, $streamFactory);

        $this->assertInstanceOf(StreamInterface::class, $stream);
        $this->assertInstanceOf(Stream::class, $stream);

        $this->assertTrue($stream->isWritable());
        $this->assertTrue($stream->isSeekable());
        $this->assertEquals('Hello world', (string) $stream);
    }

    public function testStreamWithResource()
    {
        $resource = fopen('php://temp', 'r+');
        fwrite($resource, 'Hello world');

        $stream = Factory::getStreamFactory()->createStreamFromResource($resource);

        $this->assertInstanceOf(StreamInterface::class, $stream);
        $this->assertInstanceOf(Stream::class, $stream);

        $this->assertTrue($stream->isWritable());
        $this->assertTrue($stream->isSeekable());
        $this->assertEquals('Hello world', (string) $stream);
    }

    public function testUri()
    {
        $uri = Factory::createUri('http://example.com/my-path');
        $uriFactory = Factory::getUriFactory();

        $this->assertInstanceOf(UriFactoryInterface::class, $uriFactory);
        $this->assertInstanceOf(DiactorosFactory::class, $uriFactory);

        $this->assertInstanceOf(UriInterface::class, $uri);
        $this->assertInstanceOf(Uri::class, $uri);

        $this->assertEquals('/my-path', $uri->getPath());
    }

    public function testRequest()
    {
        $serverRequest = Factory::createServerRequest('GET', '/', []);
        $serverRequestFactory = Factory::getServerRequestFactory();

        $this->assertInstanceOf(ServerRequestFactoryInterface::class, $serverRequestFactory);
        $this->assertInstanceOf(DiactorosFactory::class, $serverRequestFactory);

        $this->assertInstanceOf(ServerRequestInterface::class, $serverRequest);
        $this->assertInstanceOf(ServerRequest::class, $serverRequest);

        $this->assertEquals('/', $serverRequest->getUri()->getPath());
        $this->assertEquals('GET', $serverRequest->getMethod());
        $this->assertTrue($serverRequest->getBody()->isWritable());
        $this->assertTrue($serverRequest->getBody()->isSeekable());
    }

    public function prioritiesDataProvider(): array
    {
        return [
            [
                [
                    'diactoros' => DiactorosFactory::class,
                    'guzzle' => GuzzleFactory::class,
                    'slim' => SlimFactory::class,
                ],
                DiactorosFactory::class,
                DiactorosFactory::class,
                DiactorosFactory::class,
                DiactorosFactory::class,
            ],
            [
                [
                    'guzzle' => GuzzleFactory::class,
                    'diactoros' => DiactorosFactory::class,
                    'slim' => SlimFactory::class,
                ],
                GuzzleFactory::class,
                GuzzleFactory::class,
                GuzzleFactory::class,
                GuzzleFactory::class,
            ],
            [
                [
                    'slim' => SlimFactory::class,
                    'guzzle' => GuzzleFactory::class,
                    'diactoros' => DiactorosFactory::class,
                ],
                SlimFactory::class,
                SlimFactory::class,
                SlimFactory::class,
                SlimFactory::class,
            ],
            [
                [
                    'other' => 'Not found',
                    'slim' => SlimFactory::class,
                    'guzzle' => GuzzleFactory::class,
                    'diactoros' => DiactorosFactory::class,
                ],
                SlimFactory::class,
                SlimFactory::class,
                SlimFactory::class,
                SlimFactory::class,
            ]
        ];
    }

    /**
     * @dataProvider prioritiesDataProvider
     */
    public function testPriorities(
        array $priorities,
        $serverRequestFactoryClass,
        $responseFactoryClass,
        $streamFactoryClass,
        $uriFactoryClass
    ) {
        Factory::reset($priorities);

        $serverRequestFactory = Factory::getServerRequestFactory();
        $responseFactory = Factory::getResponseFactory();
        $streamFactory = Factory::getStreamFactory();
        $uriFactory = Factory::getUriFactory();

        $this->assertInstanceOf($serverRequestFactoryClass, $serverRequestFactory);
        $this->assertInstanceOf($responseFactoryClass, $responseFactory);
        $this->assertInstanceOf($streamFactoryClass, $streamFactory);
        $this->assertInstanceOf($uriFactoryClass, $uriFactory);
    }
}
