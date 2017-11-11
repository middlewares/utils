<?php
declare(strict_types = 1);

namespace Middlewares\Tests\CallableResolver;

final class ControllerStub
{
    public function action()
    {
    }

    public function __invoke()
    {
    }
}
