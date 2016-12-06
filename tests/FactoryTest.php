<?php

namespace Middlewares\Tests;

use Middlewares\Utils\Factory;

class FactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testResponse()
    {
        $response = Factory::createResponse();

        $this->assertInstanceOf('Psr\\Http\\Message\\ResponseInterface', $response);
        $this->assertInstanceOf('Zend\\Diactoros\\Response', $response);
    }

    public function testStream()
    {
        $stream = Factory::createStream();

        $this->assertInstanceOf('Psr\\Http\\Message\\StreamInterface', $stream);
        $this->assertInstanceOf('Zend\\Diactoros\\Stream', $stream);
    }

    public function testUri()
    {
        $uri = Factory::createUri('http://example.com/my-path');

        $this->assertInstanceOf('Psr\\Http\\Message\\UriInterface', $uri);
        $this->assertInstanceOf('Zend\\Diactoros\\Uri', $uri);
        $this->assertEquals('/my-path', $uri->getPath());
    }

    public function testRequest()
    {
        $request = Factory::createServerRequest([]);

        $this->assertInstanceOf('Psr\\Http\\Message\\ServerRequestInterface', $request);
        //$this->assertInstanceOf('Zend\\Diactoros\\Uri', $uri);
        $this->assertEquals('/', $request->getUri()->getPath());
        $this->assertEquals('GET', $request->getMethod());
    }
}
