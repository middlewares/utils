<?php

namespace Middlewares\Utils\CallableResolver;

use Interop\Container\ContainerInterface;
use RuntimeException;

/**
 * Resolve a callable using a container.
 */
final class ContainerResolver extends Resolver
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function resolve($callable, array $args = [])
    {
        if (is_string($callable)) {
            $callable = $this->resolveString($callable);
        }

        if (is_string($callable)) {
            $callable = $this->container->get($callable);
        } elseif (is_array($callable) && is_string($callable[0])) {
            $callable[0] = $this->container->get($callable[0]);
        }

        if (is_callable($callable)) {
            return $callable;
        }

        throw new RuntimeException('Invalid callable provided');
    }
}
