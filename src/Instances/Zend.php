<?php
namespace Middlewares\Utils\Instances;

/**
 * Simple class to create response instances of PSR-7 classes.
 */
class Zend extends InstanceInterface
{
    protected static $response = '\\Zend\\Diactoros\\Response';
    protected static $stream = '\\Zend\\Diactoros\\Stream';

    /**
     * Creates a Response instance.
     *
     * @param int $code The status code
     *
     * @return ResponseInterface | void
     */
    public static function response($code = 200)
    {
        if (self::isAvailable(self::$response)) {
            return new self::$response('php://memory', $code);
        }
    }
}
