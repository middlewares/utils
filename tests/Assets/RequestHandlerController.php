<?php
declare(strict_types = 1);

namespace Middlewares\Tests\Assets;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class RequestHandlerController implements RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $testcase = new TestCase('controller');
        /** @var ResponseInterface $stub */
        $stub = $testcase->makeStub(ResponseInterface::class);

        return $stub;
    }
}
