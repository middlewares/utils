<?php

namespace Middlewares\Tests\CallableResolver;

use Middlewares\Utils\CallableResolver\CallableResolverInterface;
use Middlewares\Utils\CallableResolver\ContainerResolver;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Container\ContainerInterface;

final class ContainerResolverTest extends TestCase
{
    public function testCallableResolver()
    {
        /** @var ContainerInterface|ObjectProphecy $container */
        $container = $this->prophesize(ContainerInterface::class);
        $resolver = new ContainerResolver($container->reveal());

        $this->assertInstanceOf(CallableResolverInterface::class, $resolver);
    }

    public function testResolveClass()
    {
        /** @var ContainerInterface|ObjectProphecy $container */
        $container = $this->prophesize(ContainerInterface::class);
        $resolver = new ContainerResolver($container->reveal());

        $container->get(ControllerStub::class)->willReturn(new ControllerStub());

        $callable = $resolver->resolve(ControllerStub::class);

        $this->assertInstanceOf(ControllerStub::class, $callable);
    }

    public function testResolveClassCallable()
    {
        /** @var ContainerInterface|ObjectProphecy $container */
        $container = $this->prophesize(ContainerInterface::class);
        $resolver = new ContainerResolver($container->reveal());

        $container->get(ControllerStub::class)->willReturn(new ControllerStub());

        $callable = $resolver->resolve(ControllerStub::class.'::action');

        $this->assertInstanceOf(ControllerStub::class, $callable[0]);
        $this->assertEquals('action', $callable[1]);
    }

    public function testResolveClassArrayCallable()
    {
        /** @var ContainerInterface|ObjectProphecy $container */
        $container = $this->prophesize(ContainerInterface::class);
        $resolver = new ContainerResolver($container->reveal());

        $container->get(ControllerStub::class)->willReturn(new ControllerStub());

        $callable = $resolver->resolve([ControllerStub::class, 'action']);

        $this->assertInstanceOf(ControllerStub::class, $callable[0]);
        $this->assertEquals('action', $callable[1]);
    }

    public function testResolveServiceCallable()
    {
        /** @var ContainerInterface|ObjectProphecy $container */
        $container = $this->prophesize(ContainerInterface::class);
        $resolver = new ContainerResolver($container->reveal());

        $container->get('controller')->willReturn(new ControllerStub());

        $callable = $resolver->resolve('controller::action');

        $this->assertInstanceOf(ControllerStub::class, $callable[0]);
        $this->assertEquals('action', $callable[1]);
    }

    public function testResolveServiceArrayCallable()
    {
        /** @var ContainerInterface|ObjectProphecy $container */
        $container = $this->prophesize(ContainerInterface::class);
        $resolver = new ContainerResolver($container->reveal());

        $container->get('controller')->willReturn(new ControllerStub());

        $callable = $resolver->resolve(['controller', 'action']);

        $this->assertInstanceOf(ControllerStub::class, $callable[0]);
        $this->assertEquals('action', $callable[1]);
    }

    public function testResolveObjectCallable()
    {
        /** @var ContainerInterface|ObjectProphecy $container */
        $container = $this->prophesize(ContainerInterface::class);
        $resolver = new ContainerResolver($container->reveal());

        $container->get(ControllerStub::class)->shouldNotBeCalled();

        $callable = $resolver->resolve($controllerStub = new ControllerStub());

        $this->assertSame($controllerStub, $callable);
    }

    public function testResolveObjectArrayCallable()
    {
        /** @var ContainerInterface|ObjectProphecy $container */
        $container = $this->prophesize(ContainerInterface::class);
        $resolver = new ContainerResolver($container->reveal());

        $container->get(ControllerStub::class)->shouldNotBeCalled();

        $callable = $resolver->resolve([$controllerStub = new ControllerStub(), 'action']);

        $this->assertSame($controllerStub, $callable[0]);
        $this->assertEquals('action', $callable[1]);
    }

    public function testResolveServiceClosureCallable()
    {
        /** @var ContainerInterface|ObjectProphecy $container */
        $container = $this->prophesize(ContainerInterface::class);
        $resolver = new ContainerResolver($container->reveal());

        $closure = function () {
        };

        $container->get('controller')->willReturn($closure);

        $callable = $resolver->resolve('controller');

        $this->assertSame($closure, $callable);
    }

    public function testResolveServiceFunctionCallable()
    {
        /** @var ContainerInterface|ObjectProphecy $container */
        $container = $this->prophesize(ContainerInterface::class);
        $resolver = new ContainerResolver($container->reveal());

        $container->get('controller')->willReturn('is_string');

        $callable = $resolver->resolve('controller');

        $this->assertEquals('is_string', $callable);
    }
}
