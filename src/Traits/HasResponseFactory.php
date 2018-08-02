<?php
declare(strict_types = 1);

namespace Middlewares\Utils\Traits;

use Middlewares\Utils\Factory;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Simple class to create instances of PSR-7 classes.
 */
trait HasResponseFactory
{
    /**
     * @var ResponseFactoryInterface
     */
    private $responseFactory;

    /**
     * Set the response factory used.
     */
    public function responseFactory(ResponseFactoryInterface $responseFactory): self
    {
        $this->responseFactory = $responseFactory;

        return $this;
    }

    private function createResponse(int $code = 200, string $reasonPhrase = ''): ResponseInterface
    {
        $responseFactory = $this->responseFactory ?: Factory::getResponseFactory();

        return $responseFactory->createResponse($code, $reasonPhrase);
    }
}
