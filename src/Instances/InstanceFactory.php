<?php
namespace Middlewares\Utils\Instances;

/**
 * Instances class generator
 */
class InstanceFactory
{
    /**
     * List of available instances
     *
     * @var array
     */
    private static $available = ['Zend', 'GuzzleHttp', 'Slim'];

    /**
     * Return the first available response instance
     *
     * @param int $code The status code
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public static function response($code = 200)
    {
        foreach (self::$available as $class) {
            $class = self::getClass($class);

            if ($instance = $class::response($code)) {
                return $instance;
            }
        }
    }

    /**
     * Return the first available stream instance
     *
     * @param resource $resource A resource returned by fopen
     *
     * @return \Psr\Http\Message\Stream
     */
    public static function stream($resource)
    {
        foreach (self::$available as $class) {
            $class = self::getClass($class);

            if ($instance = $class::stream($resource)) {
                return $instance;
            }
        }
    }

    /**
     * Return a full class name with namespace
     *
     * @param string $class Class name to complete with namespace
     *
     * @return string
     */
    private static function getClass($class)
    {
        return __NAMESPACE__.'\\'.$class;
    }
}
