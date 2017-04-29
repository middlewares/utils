<?php

namespace Middlewares\Tests;

use Middlewares\Utils\Factory;
use Middlewares\Utils\CallableHandler;
use Psr\Http\Message\ResponseInterface;

class CallableHandlerTest extends \PHPUnit_Framework_TestCase
{
    public function testExecute()
    {
        $response = CallableHandler::execute('sprintf', ['Hello %s', 'World']);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals('Hello World', (string) $response->getBody());
    }

    public function testOb()
    {
        $callable = function () {
            echo 'Hello';

            return ' World';
        };

        $response = CallableHandler::execute($callable);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals('Hello World', (string) $response->getBody());

        $callable = function () {
            echo 'Hello';
            ob_start();
            echo 'Hello';
            ob_start();
            echo 'Hello';
            ob_start();
            echo 'Hello';
            throw new \Exception('Error Processing Request');
        };

        ob_start();
        $level = ob_get_level();

        try {
            CallableHandler::execute($callable);
        } catch (\Exception $e) {
        }

        $this->assertSame($level, ob_get_level());
        $this->assertSame('', ob_get_clean());
    }

    public function testReturnNull()
    {
        $response = CallableHandler::execute(function () {
        });

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals('', (string) $response->getBody());
    }

    public function testReturnObjectToString()
    {
        $response = CallableHandler::execute(function () {
            return Factory::createUri('http://example.com');
        });

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals('http://example.com', (string) $response->getBody());
    }

    public function testException()
    {
        $this->expectException('UnexpectedValueException');

        CallableHandler::execute(function () {
            return ['not', 'valid', 'value'];
        });
    }
}
