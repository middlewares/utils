<?php

namespace Middlewares\Tests\CallableResolver;

use Middlewares\Utils\CallableResolver\CallableResolverInterface;
use Middlewares\Utils\CallableResolver\ReflectionResolver;
use PHPUnit\Framework\TestCase;

final class ReflectionResolverTest extends TestCase
{
    public function testCallableResolver()
    {
        $resolver = new ReflectionResolver();

        $this->assertInstanceOf(CallableResolverInterface::class, $resolver);
    }

    public function testResolveClass()
    {
        $resolver = new ReflectionResolver();

        $callable = $resolver->resolve(ControllerStub::class);

        $this->assertInstanceOf(ControllerStub::class, $callable);
    }

    public function testResolveClassCallable()
    {
        $resolver = new ReflectionResolver();

        $callable = $resolver->resolve(ControllerStub::class.'::action');

        $this->assertInstanceOf(ControllerStub::class, $callable[0]);
        $this->assertEquals('action', $callable[1]);
    }

    public function testResolveClassArrayCallable()
    {
        $resolver = new ReflectionResolver();

        $callable = $resolver->resolve([ControllerStub::class, 'action']);

        $this->assertInstanceOf(ControllerStub::class, $callable[0]);
        $this->assertEquals('action', $callable[1]);
    }

    public function testResolveObjectCallable()
    {
        $resolver = new ReflectionResolver();

        $callable = $resolver->resolve($controllerStub = new ControllerStub());

        $this->assertSame($controllerStub, $callable);
    }

    public function testResolveObjectArrayCallable()
    {
        $resolver = new ReflectionResolver();

        $callable = $resolver->resolve([$controllerStub = new ControllerStub(), 'action']);

        $this->assertSame($controllerStub, $callable[0]);
        $this->assertEquals('action', $callable[1]);
    }
}
