<?php

namespace Middlewares\Tests;

use Middlewares\Utils\CallableHandler;

class CallableHandlerTest extends \PHPUnit_Framework_TestCase
{
    public function testExecute()
    {
        $response = CallableHandler::execute('sprintf', ['Hello %s', 'World']);

        $this->assertInstanceOf('Psr\\Http\\Message\\ResponseInterface', $response);
        $this->assertEquals('Hello World', (string) $response->getBody());

        $callable = function () {
            echo 'Hello';

            return ' World';
        };

        $response = CallableHandler::execute($callable);

        $this->assertInstanceOf('Psr\\Http\\Message\\ResponseInterface', $response);
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
}
