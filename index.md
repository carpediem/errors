---
layout: default
title: errors -  Capture and convert PHP's errors into exceptions
---

Introduction
-------

`errors` helps manage errors and exceptions in PHP applications. Part of this library is based on the excellent work in the [Haldayne\Fox](https://github.com/haldayne/fox) project.

[![Source Code](http://img.shields.io/badge/source-carpediem/errors-blue.svg?style=flat-square)](https://github.com/carpediem/errors)
[![Latest Version](https://img.shields.io/github/release/carpediem/errors.svg?style=flat-square)](https://github.com/carpediem/errors/releases)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/carpediem/errors/master.svg?style=flat-square)](https://travis-ci.org/carpediem/errors)
[![HHVM Status](https://img.shields.io/hhvm/carpediem/errors.svg?style=flat-square)](http://hhvm.h4cc.de/package/carpediem/errors)
[![Coverage Status](https://img.shields.io/scrutinizer/coverage/g/carpediem/errors.svg?style=flat-square)](https://scrutinizer-ci.com/g/carpediem/errors/code-structure)
[![Quality Score](https://img.shields.io/scrutinizer/g/carpediem/errors.svg?style=flat-square)](https://scrutinizer-ci.com/g/carpediem/errors)
[![Total Downloads](https://img.shields.io/packagist/dt/carpediem/errors.svg?style=flat-square)](https://packagist.org/packages/carpediem/errors)

Usage
-------

Let's say you want to use PHP's `touch` function. This function return `false` and emit an `E_WARNING` if the file can not be created. A way to workaround this behavior is to use the `@` operator which is considered to be a bad practice as it silenced error reporting and slow down PHP execution. The `Carpediem\Errors` library helps you better handle these limitations gradually.


```php
<?php

$result = touch('/foo/bar');
//if you don't have access to '/foo' directory
// $result = false
// an E_WARNING is emitted with a associated message
```

If you want to capture the error from the `touch` function.


```php
<?php

use Carpediem\Errors\CaptureError;

$touch = new CaptureError('touch');
$result = $touch('/foo/bar');
if (!$result) {
    throw new RuntimeException($touch->getLastErrorMessage(), $touch->getLastErrorCode());
}
```

If you want to convert the error from the `touch` function into an Exception.

```php
<?php

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

Credits
-------

- [Bishop Bettini and Haldayne PHP Componentry](https://github.com/haldayne/fox)
- [All Contributors](https://github.com/carpediem/errors/graphs/contributors)

License
-------

The MIT License (MIT). Please see [LICENSE] for more information.

[PSR-2]: http://www.php-fig.org/psr/psr-2/
[PSR-4]: http://www.php-fig.org/psr/psr-4/
[LICENSE]: https://github.com/carpediem/errors/blob/master/LICENSE