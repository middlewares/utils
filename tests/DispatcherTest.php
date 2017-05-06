<?php

namespace Middlewares\Tests;

use Middlewares\Utils\Dispatcher;
use Middlewares\Utils\CallableMiddleware;
use Psr\Http\Message\ResponseInterface;

class DispatcherTest extends \PHPUnit_Framework_TestCase
{
    public function testDispatcher()
    {
        $response = Dispatcher::run([
            function ($request, $delegate) {
                $response = $delegate->process($request);
                $response->getBody()->write('3');

                return $response;
            },
            function ($request, $delegate) {
                $response = $delegate->process($request);
                $response->getBody()->write('2');

                return $response;
            },
            new CallableMiddleware(function ($request, $delegate) {
                echo '1';

                return $delegate->process($request);
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
