<?php
namespace Middlewares\Utils;

use RuntimeException;
use Psr\Http\Factory\ResponseFactoryInterface;

/**
 * Simple class to create response instances of PSR-7 classes.
 */
class ResponseFactory implements ResponseFactoryInterface
{
    /**
     * Creates a Response instance.
     *
     * @param int $code The status code
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function createResponse($code = 200)
    {
        if ($instance = Instances\InstanceFactory::response($code)) {
            return $instance;
        }

        throw new RuntimeException('Unable to create a response. No PSR-7 stream library detected');
    }
}
