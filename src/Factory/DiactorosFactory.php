<?php
declare(strict_types=1);

namespace Middlewares\Utils\Factory;

/**
 * DiactorosFactory
 *
 * @link https://github.com/zendframework/zend-diactoros
 */
class DiactorosFactory
{

    /**
     * @return bool
     */
    public static function isInstalled() : bool
    {
        return \class_exists('Zend\Diactoros\ResponseFactory')
            && \class_exists('Zend\Diactoros\ServerRequestFactory')
            && \class_exists('Zend\Diactoros\StreamFactory')
            && \class_exists('Zend\Diactoros\UriFactory');
    }

    /**
     * @var array
     */
    public static function getFactories() : array
    {
        return [
            'response' => 'Zend\Diactoros\ResponseFactory',
            'serverRequest' => 'Zend\Diactoros\ServerRequestFactory',
            'stream' => 'Zend\Diactoros\StreamFactory',
            'uri' => 'Zend\Diactoros\UriFactory',
        ];
    }
}
