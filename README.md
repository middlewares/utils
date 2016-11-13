# middlewares/utils

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Quality Score][ico-scrutinizer]][link-scrutinizer]
[![Total Downloads][ico-downloads]][link-downloads]
[![SensioLabs Insight][ico-sensiolabs]][link-sensiolabs]

Common utilities used by the middlewares' packages:

## Factory

Used to create psr-7 instances of `ResponseInterface`, `StreamInterface` and `UriInterface`. Detects automatically [Diactoros](https://github.com/zendframework/zend-diactoros), [Guzzle](https://github.com/guzzle/psr7) and [Slim](https://github.com/slimphp/Slim) but you can register a different factory using the [http-interop/http-factory](https://github.com/http-interop/http-factory) interface.

```php
use Middlewares\Utils\Factory;

$response = Factory::createResponse();
$stream = Factory::createStream();
$uri = Factory::createStream('http://example.com');

//Register other factory
Factory::setResponseFactory(new FooResponseFactory());

$fooResponse = Factory::createResponse();
```

## CallableHandler

To execute a callable and returns a response with the output. Useful to handle routes, etc:

```php
use Middlewares\Utils\CallableHandler;

$response = CallableHandler::execute(function () {
    echo 'Hello world';
});

echo $response->getBody(); //Hello world
```

---

Please see [CHANGELOG](CHANGELOG.md) for more information about recent changes and [CONTRIBUTING](CONTRIBUTING.md) for contributing details.

The MIT License (MIT). Please see [LICENSE](LICENSE) for more information.

[ico-version]: https://img.shields.io/packagist/v/middlewares/utils.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/middlewares/utils/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/g/middlewares/utils.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/middlewares/utils.svg?style=flat-square
[ico-sensiolabs]: https://img.shields.io/sensiolabs/i/3dcb2b7c-8564-48ef-9af4-d1e974762c3a.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/middlewares/utils
[link-travis]: https://travis-ci.org/middlewares/utils
[link-scrutinizer]: https://scrutinizer-ci.com/g/middlewares/utils
[link-downloads]: https://packagist.org/packages/middlewares/utils
[link-sensiolabs]: https://insight.sensiolabs.com/projects/3dcb2b7c-8564-48ef-9af4-d1e974762c3a
