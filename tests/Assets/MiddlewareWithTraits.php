<?php
declare(strict_types = 1);

namespace Middlewares\Tests\Assets;

use Middlewares\Utils\Traits\HasResponseFactory;
use Middlewares\Utils\Traits\HasStreamFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class MiddlewareWithTraits implements MiddlewareInterface
{
    use HasResponseFactory;
    use HasStreamFactory;

    /**
     * Process a request and return a response.
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        return $this->createResponse()->withBody($this->createStream());
    }
}
