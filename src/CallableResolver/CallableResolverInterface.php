<?php

namespace Middlewares\Utils\CallableResolver;

use RuntimeException;

/**
 * Simple interface to resolve callableish values.
 */
interface CallableResolverInterface
{
    /**
     * Resolves a callable.
     *
     * @param mixed $callable
     * @param array $args
     *
     * @throws RuntimeException If it's not callable
     *
     * @return callable
     */
    public function resolve($callable, array $args = []);
}
