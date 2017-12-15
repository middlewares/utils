# Change Log

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/) 
and this project adheres to [Semantic Versioning](http://semver.org/).

## [UNRELEASED]

### Added

- New class `RequestHandlerContainer` implementing PSR-11 to resolve handlers in any format (classes, callables) and return PSR-15 `RequestHandlerInterface` instances. This can be used to resolve router handlers, for example.

### Changed

- The signature of `CallableHandler` was simplified. Removed `$resolver` and `$arguments` in the constructor.

### Removed

- Deleted all callable resolvers classes. Use the `RequestHandlerContainer`, or any other PSR-11 implementation.

## [0.13.0] - 2017-11-16

### Changed

- The minimum PHP version supported is 7.0
- Replaced `http-interop/http-middleware` with  `http-interop/http-server-middleware`.
- Changed `Middlewares\Utils\CallableHandler` signature. Now it is instantiable and can be used as middleware and server request handler.

### Removed

- `Middlewares\Utils\CallableMiddleware`. Use `Middlewares\Utils\CallableHandler` instead.

## [0.12.0] - 2017-09-18

### Changed

- Append `.dist` suffix to phpcs.xml and phpunit.xml files
- Changed the configuration of phpcs and php_cs
- Upgraded phpunit to the latest version and improved its config file
- Updated `http-interop/http-middleware` to `0.5`

## [0.11.1] - 2017-05-06

### Changed

- `Middlewares\Utils\CallableHandler` expects one of the following values returned by the callable:
  * A `Psr\Http\Message\ResponseInterface`
  * `null` or scalar
  * an object with `__toString` method implemented
   Otherwise, throws an `UnexpectedValueException`
- `Middlewares\Helpers::fixContentLength` only modifies or removes the `Content-Length` header, but does not add it if didn't exist previously.

## [0.11.0] - 2017-03-25

### Added

- New class `Middlewares\Utils\Helpers` with common helpers to manipulate PSR-7 messages
- New helper `Middlewares\Utils\Helpers::fixContentLength` used to add/modify/remove the `Content-Length` header of a http message.

### Changed

- Updated `http-interop/http-factory` to `0.3`

## [0.10.1] - 2017-02-27

### Fixed

- Fixed changelog file

## [0.10.0] - 2017-02-27

### Changed

- Replaced deprecated `container-interop` by `psr/contaienr` (PSR-11).
- `Middlewares\Utils\Dispatcher` throws exceptions if the middlewares does not implement `Interop\Http\ServerMiddleware\MiddlewareInterface` or does not return an instance of `Psr\Http\Message\ResponseInterface`.
- Moved the default factories to `Middlewares\Utils\Factory` namespace.
- Minor code improvements.

## [0.9.0] - 2017-02-05

### Added

- Callable resolves to create callables from various representations

### Removed

- `Middlewares\Utils\CallableHandler::resolve`

## [0.8.0] - 2016-12-22

### Changed

- Updated `http-interop/http-middleware` to `0.4`
- Updated `friendsofphp/php-cs-fixer` to `2.0`

## [0.7.0] - 2016-12-06

### Added

- New static helper `Middlewares\Utils\Dispatcher::run` to create and dispatch a request easily

## [0.6.1] - 2016-12-06

### Fixed

- Ensure that the body of the serverRequest is writable and seekable.

## [0.6.0] - 2016-12-06

### Added

- ServerRequest factory
- `Middlewares\Utils\Dispatcher` accepts `Closure` as middleware components

### Changed

- `Middlewares\Utils\Dispatcher` creates automatically a response if the stack is exhausted

## [0.5.0] - 2016-11-22

### Added

- `Middlewares\Utils\CallableMiddleware` class, to create middlewares from callables
- `Middlewares\Utils\Dispatcher` class, to execute the middleware stack and return a response.

## [0.4.0] - 2016-11-13

### Changed

- Updated `http-interop/http-factory` to `0.2`

## [0.3.1] - 2016-10-03

### Fixed

- Bug in CallableHandler that resolve to the declaring class of a method instead the final class.

## [0.3.0] - 2016-10-03

### Added

- `Middlewares\Utils\CallableHandler` class, allowing to resolve and execute callables safely.

## [0.2.0] - 2016-10-01

### Added

- Uri factory

## 0.1.0 - 2016-09-30

### Added

- Response factory
- Stream factory

[UNRELEASED]: https://github.com/middlewares/utils/compare/v0.13.0...HEAD
[0.13.0]: https://github.com/middlewares/utils/compare/v0.12.0...v0.13.0
[0.12.0]: https://github.com/middlewares/utils/compare/v0.11.1...v0.12.0
[0.11.1]: https://github.com/middlewares/utils/compare/v0.11.0...v0.11.1
[0.11.0]: https://github.com/middlewares/utils/compare/v0.10.1...v0.11.0
[0.10.1]: https://github.com/middlewares/utils/compare/v0.10.0...v0.10.1
[0.10.0]: https://github.com/middlewares/utils/compare/v0.9.0...v0.10.0
[0.9.0]: https://github.com/middlewares/utils/compare/v0.8.0...v0.9.0
[0.8.0]: https://github.com/middlewares/utils/compare/v0.7.0...v0.8.0
[0.7.0]: https://github.com/middlewares/utils/compare/v0.6.1...v0.7.0
[0.6.1]: https://github.com/middlewares/utils/compare/v0.6.0...v0.6.1
[0.6.0]: https://github.com/middlewares/utils/compare/v0.5.0...v0.6.0
[0.5.0]: https://github.com/middlewares/utils/compare/v0.4.0...v0.5.0
[0.4.0]: https://github.com/middlewares/utils/compare/v0.3.1...v0.4.0
[0.3.1]: https://github.com/middlewares/utils/compare/v0.3.0...v0.3.1
[0.3.0]: https://github.com/middlewares/utils/compare/v0.2.0...v0.3.0
[0.2.0]: https://github.com/middlewares/utils/compare/v0.1.0...v0.2.0
