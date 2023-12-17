<?php
declare(strict_types = 1);

namespace Middlewares\Tests\Assets;

use PHPUnit\Framework\TestCase as PHPUnit_TestCase;

final class TestCase extends PHPUnit_TestCase
{
    /**
     * @param  class-string $className
     * @return mixed
     */
    public function makeStub(string $className)
    {
        return $this->createStub($className);
    }
}
