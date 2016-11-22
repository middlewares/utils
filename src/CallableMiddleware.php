<?php

namespace Middlewares\Utils;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Interop\Http\Middleware\ServerMiddlewareInterface;
use Interop\Http\Middleware\DelegateInterface;

class CallableMiddleware implements ServerMiddlewareInterface
{
    /**
     * @var callable
     */
    private $handler;

    /**
     * Constructor.
     *
     * @param callable $handler
     */
    public function __construct(callable $handler)
    {
        $this->handler = $handler;
    }

    /**
     * Process a server request and return a response.
     *
     * @param ServerRequestInterface $request
     * @param DelegateInterface      $delegate
     *
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, DelegateInterface $delegate)
    {
        return CallableHandler::execute($this->handler, [$request, $delegate]);
    }
}
