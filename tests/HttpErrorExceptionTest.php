<?php

declare(strict_types = 1);

namespace Middlewares\Tests;

use Middlewares\Utils\HttpErrorException;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class HttpErrorExceptionTest extends TestCase
{
    public function testContext(): void
    {
        // Execute
        $exception = HttpErrorException::create(500, ['context' => 'problem']);

        // Verify
        self::assertSame(500, $exception->getCode());
        self::assertSame(['context' => 'problem'], $exception->getContext());
    }

    public function testInvalid(): void
    {
        // Expect
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage((string) PHP_INT_MAX);

        // Execute
        HttpErrorException::create(PHP_INT_MAX);
    }
}
