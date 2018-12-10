<?php
declare(strict_types=1);

namespace Middlewares\Utils\Factory;

/**
 * SunriseFactory
 *
 * @link https://github.com/sunrise-php/http-message
 * @link https://github.com/sunrise-php/http-server-request
 * @link https://github.com/sunrise-php/stream
 * @link https://github.com/sunrise-php/uri
 */
class SunriseFactory
{

    /**
     * @return bool
     */
    public static function isInstalled() : bool
    {
        return \class_exists('Sunrise\Http\ServerRequest\ServerRequestFactory')
            && \class_exists('Sunrise\Http\Message\ResponseFactory')
            && \class_exists('Sunrise\Stream\StreamFactory')
            && \class_exists('Sunrise\Uri\UriFactory');
    }

    /**
     * @var array
     */
    public static function getFactories() : array
    {
        return [
            'response' => 'Sunrise\Http\Message\ResponseFactory',
            'serverRequest' => 'Sunrise\Http\ServerRequest\ServerRequestFactory',
            'stream' => 'Sunrise\Stream\StreamFactory',
            'uri' => 'Sunrise\Uri\UriFactory',
        ];
    }
}
