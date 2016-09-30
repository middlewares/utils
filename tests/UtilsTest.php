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
}
