<?php
declare(strict_types = 1);

namespace Middlewares\Tests;

use Middlewares\Utils\CallableHandler;
use Middlewares\Utils\Dispatcher;
use Middlewares\Utils\Factory;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;

class DispatcherTest extends TestCase
{
    public function testDispatcherIsInstanceOfRequestHandlerInterface(): void
    {
        $dispatcher = new Dispatcher([]);

        self::assertInstanceOf(RequestHandlerInterface::class, $dispatcher);
    }

    public function testDispatcher(): void
    {
        $response = Dispatcher::run($stack = [
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
            new CallableHandler(function ($request, $handler) {
                echo '1';

                return $handler->handle($request);
            }),
        ]);

        self::assertInstanceOf(ResponseInterface::class, $response);
        self::assertEquals('123', (string) $response->getBody());

        $response = (new Dispatcher($stack))->handle(Factory::createServerRequest('GET', '/'));

        self::assertInstanceOf(ResponseInterface::class, $response);
        self::assertEquals('123', (string) $response->getBody());
    }

    public function testMiddlewareException(): void
    {
        $this->expectException('UnexpectedValueException');

        /** @phpstan-ignore-next-line */
        $response = Dispatcher::run(['']);
    }
}
