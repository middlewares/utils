<?php
declare(strict_types = 1);

namespace Middlewares\Tests;

use Exception;
use Middlewares\Utils\CallableHandler;
use Middlewares\Utils\Factory;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

class CallableHandlerTest extends TestCase
{
    public function testExecute(): void
    {
        $callable = new CallableHandler('sprintf');
        $response = $callable('Hello %s', 'World');

        self::assertInstanceOf(ResponseInterface::class, $response);
        self::assertEquals('Hello World', (string) $response->getBody());
    }

    public function testExecuteHandler(): void
    {
        $callable = new CallableHandler(function ($request) {
            echo $request->getHeaderLine('Foo');
        });

        $request = Factory::createServerRequest('GET', '/')->withHeader('Foo', 'Bar');
        $response = $callable->handle($request);

        self::assertInstanceOf(ResponseInterface::class, $response);
        self::assertEquals('Bar', (string) $response->getBody());
    }

    public function testOb(): void
    {
        $callable = new CallableHandler(function () {
            echo 'Hello';

            return ' World';
        });

        $response = $callable();

        self::assertInstanceOf(ResponseInterface::class, $response);
        self::assertEquals('Hello World', (string) $response->getBody());

        $callable = new CallableHandler(function () {
            echo 'Hello';
            ob_start();
            echo 'Hello';
            ob_start();
            echo 'Hello';
            ob_start();
            echo 'Hello';
            throw new Exception('Error Processing Request');
        });

        ob_start();
        $level = ob_get_level();

        try {
            $callable();
        } catch (Exception $e) {
        }

        self::assertSame($level, ob_get_level());
        self::assertSame('', ob_get_clean());
    }

    public function testReturnNull(): void
    {
        $response = (new CallableHandler(function () {
        }))();

        self::assertInstanceOf(ResponseInterface::class, $response);
        self::assertEquals('', (string) $response->getBody());
    }

    public function testReturnObjectToString(): void
    {
        $response = (new CallableHandler(function () {
            return Factory::createUri('http://example.com');
        }))();

        self::assertInstanceOf(ResponseInterface::class, $response);
        self::assertEquals('http://example.com', (string) $response->getBody());
    }

    public function testException(): void
    {
        $this->expectException('UnexpectedValueException');

        (new CallableHandler(function () {
            return ['not', 'valid', 'value'];
        }))();
    }
}
