<?php

namespace Middlewares\Utils;

use Psr\Http\Message\ResponseInterface;
use RuntimeException;
use ReflectionMethod;
use ReflectionClass;

/**
 * Simple class to execute callables and returns responses
 */
abstract class CallableHandler
{
    /**
     * Resolves the callable.
     *
     * @param mixed $callable
     * @param array $args
     *
     * @throws RuntimeException If it's not callable
     *
     * @return callable
     */
    public static function resolve($callable, array $args = [])
    {
        if (is_string($callable)) {
            $class = self::resolveClass($callable);

            if (is_array($class)) {
                list($class, $method) = $class;

                $refMethod = new ReflectionMethod($class, $method);

                if (!$refMethod->isStatic()) {
                    $refClass = new ReflectionClass($class);

                    if ($refClass->hasMethod('__construct')) {
                        $instance = $refClass->newInstanceArgs($args);
                    } else {
                        $instance = $refClass->newInstance();
                    }

                    $callable = [$instance, $method];
                }
            }
        }

        if (is_callable($callable)) {
            return $callable;
        }

        throw new RuntimeException('Invalid callable provided');
    }

    /**
     * Resolves a callable class.
     *
     * @param string $class
     *
     * @return array|false
     */
    private static function resolveClass($class)
    {
        //ClassName::method
        if (strpos($class, '::') !== false) {
            list($class, $method) = explode('::', $class, 2);

            if (!class_exists($class)) {
                throw new RuntimeException("The class {$class} does not exists");
            }

            return [$class, $method];
        }

        if (class_exists($class)) {
            return [$class, '__invoke'];
        }

        return false;
    }

    /**
     * Execute the callable.
     *
     * @param callable $callable
     * @param array    $arguments
     *
     * @return ResponseInterface
     */
    public static function execute($callable, array $arguments = [])
    {
        ob_start();
        $level = ob_get_level();

        try {
            $return = call_user_func_array($callable, $arguments);

            if ($return instanceof ResponseInterface) {
                $response = $return;
                $return = '';
            } else {
                $response = Factory::createResponse();
            }

            while (ob_get_level() >= $level) {
                $return = ob_get_clean().$return;
            }

            $body = $response->getBody();

            if ($return !== '' && $body->isWritable()) {
                $body->write($return);
            }

            return $response;
        } catch (\Exception $exception) {
            while (ob_get_level() >= $level) {
                ob_end_clean();
            }

            throw $exception;
        }
    }
}
