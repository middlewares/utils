<?php
declare(strict_types = 1);

namespace Middlewares\Tests;

use Interop\Http\Server\RequestHandlerInterface;
use Middlewares\Utils\RequestHandlerContainer;
use PHPUnit\Framework\TestCase;

final class ReflectionResolverTest extends TestCase
{
    public function testResolveClass()
    {
        $resolver = new RequestHandlerContainer();

        $this->assertTrue($resolver->has(ControllerStub::class));

        $callable = $resolver->get(ControllerStub::class);

        $this->assertInstanceOf(RequestHandlerInterface::class, $callable);
    }

    public function testResolveClassCallable()
    {
        $resolver = new RequestHandlerContainer();

        $this->assertTrue($resolver->has(ControllerStub::class.'::action'));

        $callable = $resolver->get(ControllerStub::class.'::action');

        $this->assertInstanceOf(RequestHandlerInterface::class, $callable);
    }

    public function testResolveFunction()
    {
        $resolver = new RequestHandlerContainer();

        $this->assertTrue($resolver->has('printf'));

        $callable = $resolver->get('printf');

        $this->assertInstanceOf(RequestHandlerInterface::class, $callable);
    }
}
