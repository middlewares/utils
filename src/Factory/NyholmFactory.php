<?php
declare(strict_types=1);

namespace Middlewares\Utils\Factory;

/**
 * NyholmFactory
 *
 * @link https://github.com/Nyholm/psr7
 */
class NyholmFactory
{

    /**
     * @return bool
     */
    public static function isInstalled() : bool
    {
        return \class_exists('Nyholm\Psr7\Factory\Psr17Factory');
    }

    /**
     * @var array
     */
    public static function getFactories() : array
    {
        return [
            'response' => 'Nyholm\Psr7\Factory\Psr17Factory',
            'serverRequest' => 'Nyholm\Psr7\Factory\Psr17Factory',
            'stream' => 'Nyholm\Psr7\Factory\Psr17Factory',
            'uri' => 'Nyholm\Psr7\Factory\Psr17Factory',
        ];
    }
}
