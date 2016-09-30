<?php

namespace Middlewares\Utils;

use Psr\Http\Message\ResponseInterface;
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
     * @return ResponseInterface
     */
    public function createResponse($code = 200)
    {
        if (class_exists('Zend\\Diactoros\\Response')) {
            return new \Zend\Diactoros\Response('php://memory', $code);
        }

        if (class_exists('GuzzleHttp\\Psr7\\Response')) {
            return new \GuzzleHttp\Psr7\Response($code);
        }

        if (class_exists('Slim\\Http\\Response')) {
            return new \Slim\Http\Response($code);
        }

        throw new \RuntimeException('Unable to create a response. No PSR-7 stream library detected');
    }
}
