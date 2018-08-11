# middlewares/utils

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE)
[![Build Status][ico-travis]][link-travis]
[![Quality Score][ico-scrutinizer]][link-scrutinizer]
[![Total Downloads][ico-downloads]][link-downloads]
[![SensioLabs Insight][ico-sensiolabs]][link-sensiolabs]

Common utilities used by the middlewares' packages:

## Factory

Used to create psr-7 instances of `ServerRequestInterface`, `ResponseInterface`, `StreamInterface` and `UriInterface`. Detects automatically [Diactoros](https://github.com/zendframework/zend-diactoros), [Guzzle](https://github.com/guzzle/psr7), [Slim](https://github.com/slimphp/Slim) and [Nyholm/psr7](https://github.com/Nyholm/psr7) but you can register a different factory using the [psr/http-factory](https://github.com/php-fig/http-factory) interface.

```php
use Middlewares\Utils\Factory;

$request = Factory::createServerRequest('GET', '/');
$response = Factory::createResponse(200);
$stream = Factory::createStream('Hello world');
$uri = Factory::createUri('http://example.com');

// By default, detect diactoros, guzzle, slim and nyholm (in this order of priority),
// but you can change it and add other classes
Factory::setStrategy([
    'MyApp\Psr17Factory'
    Middlewares\Utils\Factory\GuzzleFactory,
    Middlewares\Utils\Factory\DiactorosFactory,
]);

//And also register directly an initialized factory
Factory::setResponseFactory(new FooResponseFactory());

$fooResponse = Factory::createResponse();

//Get the PSR-17 factory used
$uriFactory = Factory::getUriFactory();
$uri = $uriFactory->createUri('http://hello-world.com');
```

## Dispatcher

Minimalist PSR-15 compatible dispatcher. Used for testing purposes.

```php
use Middlewares\Utils\Dispatcher;

$response = Dispatcher::run([
    new Middleware1(),
    new Middleware2(),
    new Middleware3(),
    function ($request, $next) {
        $response = $next->handle($request);
        return $response->withHeader('X-Foo', 'Bar');
    }
]);
```

## CallableHandler

To resolve and execute a callable. It can be used as a middleware, server request handler or a callable:

```php
use Middlewares\Utils\CallableHandler;

$callable = new CallableHandler(function () {
    return 'Hello world';
});

$response = $callable();

echo $response->getBody(); //Hello world
```

## HttpErrorException

General purpose exception used to represent HTTP errors.

```php
use Middlewares\Utils\HttpErrorException;

$exception = HttpErrorException::create(500, [
    'problem' => 'Something bad happened',
]);

// Additional context can be get and set on the exception
$context = $exception->getContext();
```

## Traits

Common utilities shared between many middlewares like the ability to customize PSR-17 factories.

* `HasResponseFactory`
* `HasStreamFactory`

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
