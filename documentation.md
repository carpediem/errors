---
layout: default
title: Documentation
---

## CaptureError

This object enables capturing a PHP errors.

### Instantiation

#### Description

```php
<?php

public function __construct(callable $callable, int $error_level = E_ALL): CaptureError
```

Returns a new `CaptureError` object

#### Parameters

- `$callable` the callable from which we will capture the error
- `$error_level` The PHP error reporting level

If no reporting level is given, the default value used will be `E_ALL`.

#### Exceptions

- Emits an `InvalidArgumentException` if the `$error_level` is not an integer;

#### Example

```php
<?php

use Carpediem\Errors\CaptureError;

$copy = new CaptureError('copy', E_WARNING);

$lambda = new CaptureError(function ($source, $destination) {
    return copy($source, $destination);
});

$error_level = $lambda->getErrorReportingLevel();
```

### Processing the callable

#### Description

```php
<?php

public function __invoke(mixed ...$args): mixed
```

Process the registered callable

#### Parameters

You must call the method with the registered callable expected parameters.

#### Returned value

The method returns the registered callable expected return type.

#### Example

```php
<?php

use Carpediem\Errors\CaptureError;

$copy = new CaptureError('copy');
$res = $copy->__invoke('/path/to/source/file.jpg', '/path/to/dest/file.jpg');
//or
$res = $copy('/path/to/source/file.errors', '/path/to/dest/file.errors');
```

### Accessing the CaptureError properties

#### Description

```php
<?php

public function getLastErrorCode(void): int
public function getLastErrorMessage(void): string
public function getErrorReporting(void): int
```

At any given time you can you can access the level at which the error reporting is set using `CaptureError::getErrorReporting`.

If an error is emitted when executing the callable with the right error reporting level you will be able to access its code and message using the above methods:

#### Example

```php
<?php

use Carpediem\Errors\CaptureError;

$copy = new CaptureError('copy');
$res = $copy('/path/to/source/file.jpg', '/path/to/dest/file.jpg');
$copy->getLastErrorCode();
$copy->getLastErrorMessage();
$copy->getErrorCode(); //returns E_ALL;
```

If no error was caught:

- `CaptureError::getLastErrorCode` will return `0`;
- `CaptureError::getLastErrorMessage` will return an empty string;

## ErrorToException

This class serves as a wrapper to emit an exception when using a `CaptureError` object.

### Instantiation

#### Description

```php
<?php

public function __construct(
	CaptureErrorInterface $capture,
	$exceptionClassName = 'RuntimeException'
): ErrorToException
```

#### Parameters

- `$capture` an object implementing implementing the `CaptureErrorInterface` interface;
- The FQN of the exception class to emit. If none is given the object will emit a `RuntimeException`;

#### Example

```php
<?php

use Carpediem\Errors\CaptureError;
use Carpediem\Errors\ErrorToException;

$copy = new ErrorToException(new CaptureError('copy', E_WARNING), 'RuntimeException');
$exceptionName = $copy->getExceptionClassName(); //returns the string 'RuntimeException'
```

### Processing the payload

#### Description

```php
<?php

public function __invoke(mixed ...$args): mixed
```

Process the registered `captureError` object

#### Parameters

You must call the method with the registered callable expected parameters.

#### Returned value

The method returns the registered callable expected return type.

```php
<?php

use Carpediem\Errors\CaptureError;
use Carpediem\Errors\ErrorToException;

$copy = new ErrorToException(new CaptureError('copy', E_WARNING), 'RuntimeException');
$res = $copy->__invoke('/path/to/source/file.jpg', '/path/to/dest/file.jpg');
//or
$res = $copy('/path/to/source/file.errors', '/path/to/dest/file.errors');
```
If the copy can not be achieved a `RuntimeException` object will be thrown.

## CaptureErrorInterface Interface

This interface exposes the following methods:

- `CaptureErrorInterface::__invoke`
- `CaptureErrorInterface::getLastErrorCode`
- `CaptureErrorInterface::getLastErrorMessage`

As described in the `CaptureError` documentation, because the `ErrorToException` expects this interface you can easily create your own object that will throw exception depending on the result of calling the `CaptureErrorInterface::__invoke` method.
