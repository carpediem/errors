CaptureError
==========

[![Latest Version](https://img.shields.io/github/release/carpediem/errors.svg?style=flat-square)](https://github.com/carpediem/errors/releases)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/carpediem/errors/master.svg?style=flat-square)](https://travis-ci.org/carpediem/errors)
[![HHVM Status](https://img.shields.io/hhvm/carpediem/errors.svg?style=flat-square)](http://hhvm.h4cc.de/package/carpediem/errors)
[![Coverage Status](https://img.shields.io/scrutinizer/coverage/g/carpediem/errors.svg?style=flat-square)](https://scrutinizer-ci.com/g/carpediem/errors/code-structure)
[![Quality Score](https://img.shields.io/scrutinizer/g/carpediem/errors.svg?style=flat-square)](https://scrutinizer-ci.com/g/carpediem/errors)
[![Total Downloads](https://img.shields.io/packagist/dt/carpediem/errors.svg?style=flat-square)](https://packagist.org/packages/carpediem/errors)

A Class to capture error from PHP. This class is based on [Haldayne\Fox\CaptureErrors](https://github.com/haldayne/fox) class.

## Installation

The easiest way to install `Carpediem\CaptureError` is by using composer.

```bash
$ composer require carpediem\errors
```

## Requirements

You need **PHP >= 5.4.0** but the latest stable version of PHP/HHVM is recommended.

## Testing

The library has a [PHPUnit](https://phpunit.de) test suite and a coding style compliance test suite using [PHP CS Fixer](http://cs.sensiolabs.org/). To run the tests, run the following command from the project folder.

```bash
$ composer test
```

## Usage

Let's say you want to use PHP's `touch` function. This function return `false` and emit an `E_WARNING` if the file can not be created. A way to workaround this behavior is to use the `@` operator which is considered to be a bad practice as it silenced error reporting and slow down PHP execution. The `CaptureError` and the `ErrorToException` classes help you better handle these limitations.


```php
$result = touch('/foo/bar');
//if you don't have access to '/foo' directory
// $result = false
// an E_WARNING is emitted with a associated message
```

The same code using `Carpediem\Errors\CaptureError`


```php
use Carpediem\Errors\CaptureError;

$touch = new CaptureError('touch');
$result = $touch('/foo/bar');
if (!$result) {
    throw new RuntimeException($touch->getLastErrorMessage(), $touch->getLastErrorCode());
}
```

The same code using `Carpediem\Errors\ErrorToException`

```php
use Carpediem\Errors\CaptureError;
use Carpediem\Errors\ErrorToException;

$touch = new ErrorToException(new CaptureError('touch'));
try {
	$result = $touch('/foo/bar');
} catch (Exception $e) {
	echo $e->getMessage();  // the same message as CaptureError::getLastErrorMessage
	echo $e->getCode(); // the same message as CaptureError::getLastErrorCode
}
```

## Documentation

### CaptureError object

#### Instantiation

Instantiating a `CaptureError` object is as simple as calling its constructor method with two arguments:

- The callable to be used  **required**;
- The associated error reporting level as defined by PHP **optional**;

```php
use Carpediem\Errors\CaptureError;

$copy = new CaptureError('copy', E_WARNING);

$lambda = new CaptureError(function ($source, $destination) {
    return copy($source, $destination);
});

$error_level = $lambda->getErrorReportingLevel();
```

If no reporting level is given, the default value used will be `E_ALL`.
You can retrieve the current error reporting level with the `CaptureError::getErrorReportingLevel` getter method.

#### Processing the callable

To process the registered callable you need to call the `CaptureError::__invoke` method with the expected parameters for the registered callable as follow:

```php
use Carpediem\Errors\CaptureError;

$copy = new CaptureError('copy');
$res = $copy->__invoke('/path/to/source/file.jpg', '/path/to/dest/file.jpg');
//or
$res = $copy('/path/to/source/file.errors', '/path/to/dest/file.errors');
```

#### Accessing the last error properties

If a error is emitted when executing the callable with the right error reporting level you will be able to access its code and message using the following methods:

- `CaptureError::getLastErrorCode` returns PHP's associated error level;
- `CaptureError::getLastErrorMessage` returns PHP's associated error message;

```php
use Carpediem\Errors\CaptureError;

$copy = new CaptureError('copy');
$res = $copy('/path/to/source/file.jpg', '/path/to/dest/file.jpg');
$copy->getLastErrorCode();
$copy->getLastErrorMessage();
```

If no error was caught:

- `CaptureError::getLastErrorCode` will return `0`;
- `CaptureError::getLastErrorMessage` will return an empty string;

### ErrorToException object

#### Instantiation

To instantiate an `ErrorToException` object you need to specify

- a `CaptureError` object  **required**;
- The associated Exception class name you want to throw;

```php
use Carpediem\Errors\CaptureError;
use Carpediem\Errors\ErrorToException;

$copy = new ErrorToException(new CaptureError('copy', E_WARNING), 'RuntimeException');
$exceptionName = $copy->getExceptionClass(); //returns the string 'RuntimeException'
```

If no exception class is given, the default value used will be `RuntimeException`.
You can retrieve the current exception class name with the `ErrorToException::getExceptionClass` getter method.

#### Running the code

To process the registered `CaptureError` object you need to call the `ErrorToException::__invoke` method with the expected parameters as follow:

```php
use Carpediem\Errors\CaptureError;
use Carpediem\Errors\ErrorToException;

$copy = new ErrorToException(new CaptureError('copy', E_WARNING), 'RuntimeException');
$res = $copy->__invoke('/path/to/source/file.jpg', '/path/to/dest/file.jpg');
//or
$res = $copy('/path/to/source/file.errors', '/path/to/dest/file.errors');
```
If the copy can not be achieved a `RuntimeException` object will be thrown.

Contributing
-------

Contributions are welcome and will be fully credited. Please see [CONTRIBUTING](CONTRIBUTING.md) and [CONDUCT](CONDUCT.md) for details.

Security
-------

If you discover any security related issues, please email dev@carpediem.fr instead of using the issue tracker.

Credits
-------

- [Bishop Bettini and Haldayne PHP Componentry](https://github.com/haldayne/fox)
- [All Contributors](https://github.com/carpediem/errors/graphs/contributors)

License
-------

The MIT License (MIT). Please see [LICENSE](LICENSE) for more information.

[PSR-2]: http://www.php-fig.org/psr/psr-2/
[PSR-4]: http://www.php-fig.org/psr/psr-4/
