<?php
declare(strict_types = 1);

namespace Middlewares\Utils;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;

interface FactoryInterface
{
    public function getResponseFactory(): ResponseFactoryInterface;

    public function getServerRequestFactory(): ServerRequestFactoryInterface;

    public function getStreamFactory(): StreamFactoryInterface;

    public function getUriFactory(): UriFactoryInterface;
}
