<?php

namespace Middlewares\Utils;

use Interop\Http\Middleware\DelegateInterface;
use Interop\Http\Middleware\ServerMiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use LogicException;

class Dispatcher
{
    /**
     * @var ServerMiddlewareInterface[]
     */
    private $stack;

    /**
     * @param ServerMiddlewareInterface[] $stack middleware stack (with at least one middleware component)
     */
    public function __construct($stack)
    {
        assert(count($stack) > 0);

        $this->stack = $stack;
    }

    /**
     * Dispatches the middleware stack and returns the resulting `ResponseInterface`.
     *
     * @param ServerRequestInterface $request
     *
     * @return ResponseInterface
     */
    public function dispatch(ServerRequestInterface $request)
    {
        $resolved = $this->resolve(0);

        return $resolved->process($request);
    }

    /**
     * @param int $index middleware stack index
     *
     * @return DelegateInterface
     */
    private function resolve($index)
    {
        if (isset($this->stack[$index])) {
            return new Delegate(function (ServerRequestInterface $request) use ($index) {
                $middleware = $this->stack[$index];

                assert($middleware instanceof ServerMiddlewareInterface);

                $result = $middleware->process($request, $this->resolve($index + 1));

                assert($result instanceof ResponseInterface);

                return $result;
            });
        }

        return new Delegate(function () {
            throw new LogicException('unresolved request: middleware stack exhausted with no result');
        });
    }
}
