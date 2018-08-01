<?php
declare(strict_types = 1);

namespace Middlewares\Utils;

use Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Server\RequestHandlerInterface;
use ReflectionClass;
use ReflectionMethod;

/**
 * Resolve a callable using a container.
 */
class RequestHandlerContainer implements ContainerInterface
{
    protected $arguments;

    /**
     * @param array $arguments Arguments passed to the request handler constructor
     */
    public function __construct(array $arguments = [])
    {
        $this->arguments = $arguments;
    }

    /**
     * {@inheritdoc}
     */
    public function has($id)
    {
        $id = $this->split($id);

        if (is_string($id)) {
            return function_exists($id) || class_exists($id);
        }

        return class_exists($id[0]) && method_exists($id[0], $id[1]);
    }

    /**
     * {@inheritdoc}
     *
     * @return RequestHandlerInterface
     */
    public function get($id)
    {
        try {
            $handler = $this->resolve($id);

            if ($handler instanceof RequestHandlerInterface) {
                return $handler;
            }

            return new CallableHandler($handler);
        } catch (NotFoundExceptionInterface $exception) {
            throw $exception;
        } catch (Exception $exception) {
            throw new class("Error getting the handler $id", 0, $exception) extends Exception implements
                ContainerExceptionInterface {
            };
        }
    }

    /**
     * Resolves a handler
     *
     * @param string $handler
     *
     * @return mixed
     */
    protected function resolve(string $handler)
    {
        $handler = $this->split($handler);

        if (is_string($handler)) {
            return function_exists($handler) ? $handler : $this->createClass($handler);
        }

        list($class, $method) = $handler;

        if ((new ReflectionMethod($class, $method))->isStatic()) {
            return $handler;
        }

        return [$this->createClass($class), $method];
    }

    /**
     * Returns the instance of a class.
     *
     * @return object
     */
    protected function createClass(string $className)
    {
        if (!class_exists($className)) {
            throw new class("The class $className does not exists") extends Exception implements
                NotFoundExceptionInterface {
            };
        }

        $reflection = new ReflectionClass($className);

        if ($reflection->hasMethod('__construct')) {
            return $reflection->newInstanceArgs($this->arguments);
        }

        return $reflection->newInstance();
    }

    /**
     * Slit a string to an array
     *
     * @return string|string[]
     */
    protected function split(string $string)
    {
        //ClassName/Service::method
        if (strpos($string, '::') === false) {
            return $string;
        }

        return explode('::', $string, 2);
    }
}
