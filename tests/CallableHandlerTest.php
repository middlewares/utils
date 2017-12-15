<?php
declare(strict_types = 1);

namespace Middlewares\Tests;

use Middlewares\Utils\CallableHandler;
use Middlewares\Utils\Factory;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

class CallableHandlerTest extends TestCase
{
    public function testExecute()
    {
        $callable = new CallableHandler('sprintf');
        $response = $callable('Hello %s', 'World');

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals('Hello World', (string) $response->getBody());
    }

    public function testOb()
    {
        $callable = new CallableHandler(function () {
            echo 'Hello';

            return ' World';
        });

        $response = $callable();

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals('Hello World', (string) $response->getBody());

        $callable = new CallableHandler(function () {
            echo 'Hello';
            ob_start();
            echo 'Hello';
            ob_start();
            echo 'Hello';
            ob_start();
            echo 'Hello';
            throw new \Exception('Error Processing Request');
        });

        ob_start();
        $level = ob_get_level();

        try {
            $callable();
        } catch (\Exception $e) {
        }

        $this->assertSame($level, ob_get_level());
        $this->assertSame('', ob_get_clean());
    }

    public function testReturnNull()
    {
        $response = (new CallableHandler(function () {
        }))();

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals('', (string) $response->getBody());
    }

    public function testReturnObjectToString()
    {
        $response = (new CallableHandler(function () {
            return Factory::createUri('http://example.com');
        }))();

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals('http://example.com', (string) $response->getBody());
    }

    public function testException()
    {
        $this->expectException('UnexpectedValueException');

        (new CallableHandler(function () {
            return ['not', 'valid', 'value'];
        }))();
    }
}
