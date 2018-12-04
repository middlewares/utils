<?php
declare(strict_types = 1);

namespace Middlewares\Tests;

use Middlewares\Utils\Factory;
use Middlewares\Utils\Factory\GuzzleFactory;
use Middlewares\Utils\Factory\SlimFactory;
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

class FactoryTest extends TestCase
{
    public function testResponse()
    {
        $response = Factory::createResponse();
        $responseFactory = Factory::getResponseFactory();

        $this->assertInstanceOf(ResponseFactoryInterface::class, $responseFactory);
        $this->assertInstanceOf(ResponseFactory::class, $responseFactory);

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
        $this->assertInstanceOf(StreamFactory::class, $streamFactory);

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
        $this->assertInstanceOf(UriFactory::class, $uriFactory);

        $this->assertInstanceOf(UriInterface::class, $uri);
        $this->assertInstanceOf(Uri::class, $uri);

        $this->assertEquals('/my-path', $uri->getPath());
    }

    public function testRequest()
    {
        $serverRequest = Factory::createServerRequest('GET', '/', []);
        $serverRequestFactory = Factory::getServerRequestFactory();

        $this->assertInstanceOf(ServerRequestFactoryInterface::class, $serverRequestFactory);
        $this->assertInstanceOf(ServerRequestFactory::class, $serverRequestFactory);

        $this->assertInstanceOf(ServerRequestInterface::class, $serverRequest);
        $this->assertInstanceOf(ServerRequest::class, $serverRequest);

        $this->assertEquals('/', $serverRequest->getUri()->getPath());
        $this->assertEquals('GET', $serverRequest->getMethod());
        $this->assertTrue($serverRequest->getBody()->isWritable());
        $this->assertTrue($serverRequest->getBody()->isSeekable());
    }

    public function strategiesDataProvider(): array
    {
        return [
            [
                [
                    [
                        'serverRequest' => ServerRequestFactory::class,
                        'response' => ResponseFactory::class,
                        'stream' => StreamFactory::class,
                        'uri' => UriFactory::class,
                    ],
                    GuzzleFactory::class,
                    SlimFactory::class,
                ],
                ServerRequestFactory::class,
                ResponseFactory::class,
                StreamFactory::class,
                UriFactory::class,
            ],
            [
                [
                    GuzzleFactory::class,
                    SlimFactory::class,
                ],
                GuzzleFactory::class,
                GuzzleFactory::class,
                GuzzleFactory::class,
                GuzzleFactory::class,
            ],
            [
                [
                    SlimFactory::class,
                    GuzzleFactory::class,
                ],
                SlimFactory::class,
                SlimFactory::class,
                SlimFactory::class,
                SlimFactory::class,
            ],
            [
                [
                    'NotFound',
                    [
                        'serverRequest' => SlimFactory::class,
                        'response' => ResponseFactory::class,
                        'stream' => SlimFactory::class,
                        'uri' => GuzzleFactory::class,
                    ],
                ],
                SlimFactory::class,
                ResponseFactory::class,
                SlimFactory::class,
                GuzzleFactory::class,
            ],
        ];
    }

    /**
     * @dataProvider strategiesDataProvider
     */
    public function testStrategies(
        array $strategies,
        string $serverRequestFactoryClass,
        string $responseFactoryClass,
        string $streamFactoryClass,
        string $uriFactoryClass
    ) {
        Factory::setStrategies($strategies);

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
