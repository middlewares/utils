<?php
namespace Middlewares\Utils\Instances;

/**
 * Simple class to create response instances of PSR-7 classes.
 */
class Slim extends InstanceInterface
{
    protected static $response = '\\Slim\\Http\\Response';
    protected static $stream = '\\Slim\\Http\\Stream';
}
