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

final class ReflectionResolverTest extends TestCase
{
    public function testResolveClass()
    {
        $resolver = new RequestHandlerContainer();

        $this->assertTrue($resolver->has(Controller::class));

        $callable = $resolver->get(Controller::class);

        $this->assertInstanceOf(RequestHandlerInterface::class, $callable);
    }

    public function testResolveClassCallable()
    {
        $resolver = new RequestHandlerContainer();

        $this->assertTrue($resolver->has(Controller::class.'::action'));

        $callable = $resolver->get(Controller::class.'::action');

        $this->assertInstanceOf(RequestHandlerInterface::class, $callable);
    }

    public function testResolveClassStaticCallable()
    {
        $resolver = new RequestHandlerContainer();

        $this->assertTrue($resolver->has(Controller::class.'::staticAction'));

        $callable = $resolver->get(Controller::class.'::staticAction');

        $this->assertInstanceOf(RequestHandlerInterface::class, $callable);
    }

    public function testResolveRequestHandler()
    {
        $resolver = new RequestHandlerContainer();

        $this->assertTrue($resolver->has(RequestHandlerController::class));

        $callable = $resolver->get(RequestHandlerController::class);

        $this->assertInstanceOf(RequestHandlerInterface::class, $callable);
    }

    public function testResolveFunction()
    {
        $resolver = new RequestHandlerContainer();

        $this->assertTrue($resolver->has('printf'));

        $callable = $resolver->get('printf');

        $this->assertInstanceOf(RequestHandlerInterface::class, $callable);
    }

    public function testContainerExceptionInterface()
    {
        $resolver = new RequestHandlerContainer();

        $this->assertTrue($resolver->has(ErrorController::class));

        $this->expectException(ContainerExceptionInterface::class);

        $callable = $resolver->get(ErrorController::class);
    }

    public function testNotFoundException()
    {
        $resolver = new RequestHandlerContainer();

        $this->assertFalse($resolver->has('foo'));

        $this->expectException(NotFoundExceptionInterface::class);

        $resolver->get('foo');
    }
}
