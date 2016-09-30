<?php
namespace Middlewares\Utils\Instances;

/**
 * Simple class to create response instances of PSR-7 classes.
 */
class GuzzleHttp extends InstanceInterface
{
    protected static $response = '\\GuzzleHttp\Psr7\\Response';
    protected static $stream = '\\GuzzleHttp\Psr7\\Stream';
}
