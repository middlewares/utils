<?php
declare(strict_types = 1);

namespace Middlewares\Tests;

use Middlewares\Tests\Assets\Controller;
use Middlewares\Tests\Assets\ErrorController;
use Middlewares\Tests\Assets\RequestHandlerController;
use Middlewares\Utils\RequestHandlerContainer;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class RequestHandlerContainerTest extends TestCase
{
    public function testResolveClass(): void
    {
        $resolver = new RequestHandlerContainer();

        self::assertTrue($resolver->has(Controller::class));

        $callable = $resolver->get(Controller::class);

        self::assertInstanceOf(RequestHandlerInterface::class, $callable);
    }

    public function testResolveClassCallable(): void
    {
        $resolver = new RequestHandlerContainer();

        self::assertTrue($resolver->has(Controller::class.'::action'));

        $callable = $resolver->get(Controller::class.'::action');

        self::assertInstanceOf(RequestHandlerInterface::class, $callable);
    }

    public function testResolveClassStaticCallable(): void
    {
        $resolver = new RequestHandlerContainer();

        self::assertTrue($resolver->has(Controller::class.'::staticAction'));

        $callable = $resolver->get(Controller::class.'::staticAction');

        self::assertInstanceOf(RequestHandlerInterface::class, $callable);
    }

    public function testResolveRequestHandler(): void
    {
        $resolver = new RequestHandlerContainer();

        self::assertTrue($resolver->has(RequestHandlerController::class));

        $callable = $resolver->get(RequestHandlerController::class);

        self::assertInstanceOf(RequestHandlerInterface::class, $callable);
    }

    public function testResolveFunction(): void
    {
        $resolver = new RequestHandlerContainer();

        self::assertTrue($resolver->has('printf'));

        $callable = $resolver->get('printf');

        self::assertInstanceOf(RequestHandlerInterface::class, $callable);
    }

    public function testContainerExceptionInterface(): void
    {
        $resolver = new RequestHandlerContainer();

        self::assertTrue($resolver->has(ErrorController::class));

        $this->expectException(ContainerExceptionInterface::class);

        $callable = $resolver->get(ErrorController::class);
    }

    public function testNotFoundException(): void
    {
        $resolver = new RequestHandlerContainer();

        self::assertFalse($resolver->has('foo'));

        $this->expectException(NotFoundExceptionInterface::class);

        $resolver->get('foo');
    }
}
