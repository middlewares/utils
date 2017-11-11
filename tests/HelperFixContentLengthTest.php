<?php
declare(strict_types = 1);

namespace Middlewares\Tests;

use Middlewares\Utils\Factory;
use Middlewares\Utils\Helpers;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\CallbackStream;

class HelperFixContentLengthTest extends TestCase
{
    public function testReplaceContentLength()
    {
        $response = Factory::createResponse()
            ->withHeader('Content-Length', '22');

        $response->getBody()->write('Hello world');
        $response = Helpers::fixContentLength($response);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals('11', $response->getHeaderLine('Content-Length'));
    }

    public function testBodyWithoutSize()
    {
        $response = Factory::createResponse()
            ->withBody(new CallbackStream(function () {
            }));

        $response = Helpers::fixContentLength($response);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertFalse($response->hasHeader('Content-Length'));
    }

    public function testResponseWithoutHeader()
    {
        $response = Factory::createResponse();
        $response->getBody()->write('Hello world');
        $response = Helpers::fixContentLength($response);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertFalse($response->hasHeader('Content-Length'));
    }

    public function testRemoveContentLength()
    {
        $response = Factory::createResponse()
            ->withBody(new CallbackStream(function () {
            }))
            ->withHeader('Content-Length', '22');

        $response = Helpers::fixContentLength($response);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertFalse($response->hasHeader('Content-Length'));
    }
}
