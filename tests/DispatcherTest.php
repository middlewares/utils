<?php

namespace Middlewares\Tests;

use Middlewares\Utils\CallableMiddleware;
use Middlewares\Utils\Dispatcher;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

class DispatcherTest extends TestCase
{
    public function testDispatcher()
    {
        $response = Dispatcher::run([
            function ($request, $handler) {
                $response = $handler->handle($request);
                $response->getBody()->write('3');

                return $response;
            },
            function ($request, $handler) {
                $response = $handler->handle($request);
                $response->getBody()->write('2');

                return $response;
            },
            new CallableMiddleware(function ($request, $handler) {
                echo '1';

                return $handler->handle($request);
            }),
        ]);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals('123', (string) $response->getBody());
    }

    public function testMiddlewareException()
    {
        $this->expectException('UnexpectedValueException');

        $response = Dispatcher::run(['']);
    }
}
