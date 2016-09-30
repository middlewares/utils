<?php
namespace Middlewares\Utils\Instances;

/**
 * Interface to available instances
 */
abstract class InstanceInterface
{
    /**
     * Creates a Response instance.
     *
     * @param int $code The status code
     *
     * @return \Psr\Http\Message\ResponseInterface | void
     */
    public static function response($code = 200)
    {
        if (self::isAvailable(static::$response)) {
            return new static::$response($code);
        }
    }

    /**
     * Creates a Stream instance.
     *
     * @param resource $resource A resource returned by fopen
     *
     * @return \Psr\Http\Message\StreamInterface | void
     */
    public static function stream($resource)
    {
        if (self::isAvailable(static::$stream)) {
            return new static::$stream($resource);
        }
    }

    /**
     * Check if a class is available.
     *
     * @param string $class Class name to check
     *
     * @return boolean
     */
    protected static function isAvailable($class)
    {
        return class_exists($class);
    }
}
