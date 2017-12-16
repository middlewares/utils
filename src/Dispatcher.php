<?php
declare(strict_types = 1);

namespace Middlewares\Utils;

use Closure;
use Interop\Http\Server\MiddlewareInterface;
use Interop\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use UnexpectedValueException;

class Dispatcher
{
    /**
     * @var MiddlewareInterface[]
     */
    private $stack;

    /**
     * Static helper to create and dispatch a request.
     */
    public static function run(array $stack, ServerRequestInterface $request = null): ResponseInterface
    {
        if ($request === null) {
            $request = Factory::createServerRequest();
        }

        return (new static($stack))->dispatch($request);
    }

    /**
     * @param MiddlewareInterface[] $stack middleware stack (with at least one middleware component)
     */
    public function __construct(array $stack)
    {
        $this->stack = $stack;
    }

    /**
     * Dispatches the middleware stack and returns the resulting `ResponseInterface`.
     */
    public function dispatch(ServerRequestInterface $request): ResponseInterface
    {
        $resolved = $this->resolve(0);

        return $resolved->handle($request);
    }

    /**
     * @param int $index middleware stack index
     *
     * @return RequestHandlerInterface
     */
    private function resolve(int $index): RequestHandlerInterface
    {
        return new RequestHandler(function (ServerRequestInterface $request) use ($index) {
            $middleware = isset($this->stack[$index]) ? $this->stack[$index] : new CallableHandler(function () {
            });

            if ($middleware instanceof Closure) {
                $middleware = new CallableHandler($middleware);
            }

            if (!($middleware instanceof MiddlewareInterface)) {
                throw new UnexpectedValueException(
                    sprintf('The middleware must be an instance of %s', MiddlewareInterface::class)
                );
            }

            return $middleware->process($request, $this->resolve($index + 1));
        });
    }
}
