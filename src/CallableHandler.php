<?php
declare(strict_types = 1);

namespace Middlewares\Utils;

use Exception;
use Interop\Http\Server\MiddlewareInterface;
use Interop\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use UnexpectedValueException;

/**
 * Simple class to execute callables as middlewares or request handlers.
 */
class CallableHandler implements MiddlewareInterface, RequestHandlerInterface
{
    private $callable;

    public function __construct(callable $callable)
    {
        $this->callable = $callable;
    }

    /**
     * {@inheritdoc}
     *
     * Process a server request and return a response.
     * @see RequestHandlerInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return self::execute($this->callable, [$request]);
    }

    /**
     * {@inheritdoc}
     *
     * Process a server request and return a response.
     * @see MiddlewareInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        return self::execute($this->callable, [$request, $handler]);
    }

    /**
     * Magic method to invoke the callable directly
     */
    public function __invoke(): ResponseInterface
    {
        return self::execute($this->callable, func_get_args());
    }

    /**
     * Execute the callable.
     */
    private static function execute(callable $callable, array $arguments = []): ResponseInterface
    {
        ob_start();
        $level = ob_get_level();

        try {
            $return = call_user_func_array($callable, $arguments);

            if ($return instanceof ResponseInterface) {
                $response = $return;
                $return = '';
            } elseif (is_null($return)
                 || is_scalar($return)
                 || (is_object($return) && method_exists($return, '__toString'))
            ) {
                $response = Factory::createResponse();
            } else {
                throw new UnexpectedValueException(
                    'The value returned must be scalar or an object with __toString method'
                );
            }

            while (ob_get_level() >= $level) {
                $return = ob_get_clean().$return;
            }

            $body = $response->getBody();

            if ($return !== '' && $body->isWritable()) {
                $body->write($return);
            }

            return $response;
        } catch (Exception $exception) {
            while (ob_get_level() >= $level) {
                ob_end_clean();
            }

            throw $exception;
        }
    }
}
