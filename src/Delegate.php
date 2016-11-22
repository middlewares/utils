<?php

namespace Middlewares\Utils;

use Interop\Http\Middleware\DelegateInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class Delegate implements DelegateInterface
{
    /**
     * @var callable
     */
    private $callback;

    /**
     * @param callable $callback function (RequestInterface $request) : ResponseInterface
     */
    public function __construct(callable $callback)
    {
        $this->callback = $callback;
    }

    public function process(RequestInterface $request)
    {
        return call_user_func($this->callback, $request);
    }
}
