<?php
declare(strict_types = 1);

namespace Middlewares\Utils\Factory;

use Interop\Http\Factory\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use RuntimeException;

/**
 * Simple class to create response instances of PSR-7 classes.
 */
class ResponseFactory implements ResponseFactoryInterface
{
    public function createResponse(int $code = 200, string $reasonPhrase = ''): ResponseInterface
    {
        if (class_exists('Zend\\Diactoros\\Response')) {
            $response = new \Zend\Diactoros\Response('php://memory', $code);

            return $reasonPhrase !== '' ? $response->withStatus($code, $reasonPhrase) : $response;
        }

        if (class_exists('GuzzleHttp\\Psr7\\Response')) {
            return new \GuzzleHttp\Psr7\Response($code, [], null, '1.1', $reasonPhrase);
        }

        if (class_exists('Slim\\Http\\Response')) {
            $response = new \Slim\Http\Response($code);

            return $reasonPhrase !== '' ? $response->withStatus($code, $reasonPhrase) : $response;
        }

        throw new RuntimeException('Unable to create a response. No PSR-7 library detected');
    }
}
