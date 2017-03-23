<?php

namespace Middlewares\Tests;

use Middlewares\Utils\Helpers;
use Middlewares\Utils\Factory;
use Zend\Diactoros\CallbackStream;

class HelperFixContentLengthTest extends \PHPUnit_Framework_TestCase
{
    public function testAddContentLength()
    {
        $response = Factory::createResponse();
        $response->getBody()->write('Hello world');
        $response = Helpers::fixContentLength($response);

        $this->assertInstanceOf('Psr\\Http\\Message\\ResponseInterface', $response);
        $this->assertEquals('11', $response->getHeaderLine('Content-Length'));
    }

    public function testReplaceContentLength()
    {
        $response = Factory::createResponse()
            ->withHeader('Content-Length', '22');

        $response->getBody()->write('Hello world');
        $response = Helpers::fixContentLength($response);

        $this->assertInstanceOf('Psr\\Http\\Message\\ResponseInterface', $response);
        $this->assertEquals('11', $response->getHeaderLine('Content-Length'));
    }

    public function testNotAddContentLength()
    {
        $response = Factory::createResponse()
            ->withBody(new CallbackStream(function () {
            }));

        $response = Helpers::fixContentLength($response);

        $this->assertInstanceOf('Psr\\Http\\Message\\ResponseInterface', $response);
        $this->assertFalse($response->hasHeader('Content-Length'));
    }

    public function testRemoveContentLength()
    {
        $response = Factory::createResponse()
            ->withBody(new CallbackStream(function () {
            }))
            ->withHeader('Content-Length', '22');

        $response = Helpers::fixContentLength($response);

        $this->assertInstanceOf('Psr\\Http\\Message\\ResponseInterface', $response);
        $this->assertFalse($response->hasHeader('Content-Length'));
    }
}
