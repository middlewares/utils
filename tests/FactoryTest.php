<?php
declare(strict_types = 1);

namespace Middlewares\Tests;

use Middlewares\Utils\Factory;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequest;
use Zend\Diactoros\Stream;
use Zend\Diactoros\Uri;

class FactoryTest extends TestCase
{
    public function testResponse()
    {
        $response = Factory::createResponse();

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertTrue($response->getBody()->isWritable());
        $this->assertTrue($response->getBody()->isSeekable());
    }

    public function testStream()
    {
        $stream = Factory::createStream();

        $this->assertInstanceOf(StreamInterface::class, $stream);
        $this->assertInstanceOf(Stream::class, $stream);
        $this->assertTrue($stream->isWritable());
        $this->assertTrue($stream->isSeekable());
    }

    public function testUri()
    {
        $uri = Factory::createUri('http://example.com/my-path');

        $this->assertInstanceOf(UriInterface::class, $uri);
        $this->assertInstanceOf(Uri::class, $uri);
        $this->assertEquals('/my-path', $uri->getPath());
    }

    public function testRequest()
    {
        $request = Factory::createServerRequest([]);

        $this->assertInstanceOf(ServerRequestInterface::class, $request);
        $this->assertInstanceOf(ServerRequest::class, $request);
        $this->assertEquals('/', $request->getUri()->getPath());
        $this->assertEquals('GET', $request->getMethod());
        $this->assertTrue($request->getBody()->isWritable());
        $this->assertTrue($request->getBody()->isSeekable());
    }
}
