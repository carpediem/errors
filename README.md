CaptureError
==========

A Class to capture error from PHP. This class is based on [Haldayne\Fox\CaptureErrors](https://github.com/haldayne/fox) class.

## Installation

The easiest way to install `Carpediem\CaptureError` is by using composer.

```bash
$ composer require carpediem\capture-error
```

## Requirements

You need **PHP >= 5.4.0** but the latest stable version of PHP/HHVM is recommended.

## Testing

`CaptureError` has a [PHPUnit](https://phpunit.de) test suite and a coding style compliance test suite using [PHP CS Fixer](http://cs.sensiolabs.org/). To run the tests, run the following command from the project folder.

```bash
$ composer test
```

## Usage

Let's say you want to use PHP's `touch` function. This function return `false` and emit an `E_WARNING` if the file can not be created. A way to workaround this behavior is to use the `@` operator which is considered to be a bad practice as it silenced error reporting and slow down PHP execution. The `CaptureError` class helps you better handle these limitations.


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

## Documentation

### Instantiation

Instantiating a `CaptureError` object is as simple as calling its constructor method with two arguments:

- The callable to be used  **required**;
- The associated error reporting level as defined by PHP **optional**;

```php
use Carpediem\Errors\CaptureError;

$copy = new CaptureError('copy', E_WARNING);

$lambda = new CaptureError(function ($source, $destination) {
    return copy($source, $destination);
});
```

If no reporting level is given, the default value used will be `E_ALL`.

### Setting the error reporting level

At any given time you can adjust the error reporting level using the `CaptureError::setErrorReportingLevel`. The method expects its single argument to be an integer that represents a supported PHP error level.

```php
use Carpediem\Errors\CaptureError;

$copy = new CaptureError('copy');
$copy->setErrorReportingLevel(E_ALL | E_NOTICE);
$error_level = $copy->getErrorReportingLevel();
```
You can retrieve the current error reporting level with the `CaptureError::getErrorReportingLevel` getter method.

### Processing the callable

To process the registered callable you need to call the `CaptureError::__invoke` method with the expected parameters for the registered callable as follow:

```php
use Carpediem\Errors\CaptureError;

$copy = new CaptureError('copy');
$res = $copy->__invoke('/path/to/source/file.jpg', '/path/to/dest/file.jpg');
//or
$res = $copy('/path/to/source/file.csv', '/path/to/dest/file.csv');
```

### Accessing the last error properties

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
