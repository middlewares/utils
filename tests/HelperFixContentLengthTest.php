<?php

namespace Middlewares\Tests;

use Middlewares\Utils\Helpers;
use Middlewares\Utils\Factory;
use Zend\Diactoros\CallbackStream;
use Psr\Http\Message\ResponseInterface;

class HelperFixContentLengthTest extends \PHPUnit_Framework_TestCase
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
