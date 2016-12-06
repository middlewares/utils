# Change Log
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/) 
and this project adheres to [Semantic Versioning](http://semver.org/).

## 0.6.0 - 2016-12-06

### Added

* ServerRequest factory
* `Middlewares\Utils\Dispatcher` accepts `Closure` as middleware components

### Changed
* `Middlewares\Utils\Dispatcher` creates automatically a response if the stack is exhausted

## 0.5.0 - 2016-11-22

### Added

* `Middlewares\Utils\CallableMiddleware` class, to create middlewares from callables
* `Middlewares\Utils\Dispatcher` class, to execute the middleware stack and return a response.

## 0.4.0 - 2016-11-13

### Changed
* Updated `http-interop/http-factory` to `0.2`

## 0.3.1 - 2016-10-03

### Fixed
* Bug in CallableHandler that resolve to the declaring class of a method instead the final class.

## 0.3.0 - 2016-10-03

### Added
* `Middlewares\Utils\CallableHandler` class, allowing to resolve and execute callables safely.

## 0.2.0 - 2016-10-01

### Added
* Uri factory

## 0.1.0 - 2016-09-30

### Added
* Response factory
* Stream factory
