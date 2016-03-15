Errors
==========

[![Latest Version](https://img.shields.io/github/release/carpediem/errors.svg?style=flat-square)](https://github.com/carpediem/errors/releases)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/carpediem/errors/master.svg?style=flat-square)](https://travis-ci.org/carpediem/errors)
[![HHVM Status](https://img.shields.io/hhvm/carpediem/errors.svg?style=flat-square)](http://hhvm.h4cc.de/package/carpediem/errors)
[![Coverage Status](https://img.shields.io/scrutinizer/coverage/g/carpediem/errors.svg?style=flat-square)](https://scrutinizer-ci.com/g/carpediem/errors/code-structure)
[![Quality Score](https://img.shields.io/scrutinizer/g/carpediem/errors.svg?style=flat-square)](https://scrutinizer-ci.com/g/carpediem/errors)
[![Total Downloads](https://img.shields.io/packagist/dt/carpediem/errors.svg?style=flat-square)](https://packagist.org/packages/carpediem/errors)

This library helps capturing and transforming PHP's error into exception. Part of this library is based on the excellent work in the [Haldayne\Fox](https://github.com/haldayne/fox) project.

Highlights
----------

- Enables manipulating PHP's errors in a predicable way

Requirements
----------

You need **PHP >= 5.4.0** but the latest stable version of PHP/HHVM is recommended.

Installation
----------

The easiest way to install `Carpediem\Errors` is by using composer.

```bash
$ composer require carpediem/errors
```

Documentation
-------

Full documentation can be found at [carpediem.github.io/errors](http://carpediem.github.io/errors). Contribute to this documentation in the [gh-pages branch](https://github.com/carpediem/errors/tree/gh-pages)

Testing
-------

The library has a [PHPUnit](https://phpunit.de) test suite and a coding style compliance test suite using [PHP CS Fixer](http://cs.sensiolabs.org/). To run the tests, run the following command from the project folder.

```bash
$ composer test
```
Contributing
-------

Contributions are welcome and will be fully credited. Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

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
