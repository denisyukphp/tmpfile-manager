# TmpFileManager

[![Build Status](https://img.shields.io/travis/com/denisyukphp/tmpfile-manager/master?style=plastic)](https://app.travis-ci.com/denisyukphp/tmpfile-manager)
[![Latest Stable Version](https://img.shields.io/packagist/v/denisyukphp/tmpfile-manager?style=plastic)](https://packagist.org/packages/denisyukphp/tmpfile-manager)
[![Packagist PHP Version Support](https://img.shields.io/packagist/php-v/denisyukphp/tmpfile-manager?style=plastic&color=8892BF)](https://packagist.org/packages/denisyukphp/tmpfile-manager)
[![Total Downloads](https://img.shields.io/packagist/dt/denisyukphp/tmpfile-manager?style=plastic)](https://packagist.org/packages/denisyukphp/tmpfile-manager)
[![License](https://img.shields.io/packagist/l/denisyukphp/tmpfile-manager?style=plastic&color=428F7E)](https://packagist.org/packages/denisyukphp/tmpfile-manager)

Temp files manager.

## Installation

You can install the latest version via [Composer](https://getcomposer.org/):

```text
composer require denisyukphp/tmpfile-manager
```

This package requires PHP 8.1 or later.

## Quick usage

Configure TmpFileManager and create a temp file:

```php
<?php

use TmpFileManager\Config\Config;
use TmpFileManager\TmpFileManager;
use TmpFile\TmpFileInterface;

$config = new Config(
    tmpFileDirectory: sys_get_temp_dir(),
    tmpFilePrefix: 'php',
);

$tmpFileManager = new TmpFileManager($config);

/** @var TmpFileInterface $tmpFile */
$tmpFile = $tmpFileManager->create();
```

All temp files which created with the manager will be purged automatically by default.

## Documentation

- [Default configuration](docs/index.md#default-configuration)
- [Creating temp files](docs/index.md#creating-temp-files)
- [Removing temp files](docs/index.md#removing-temp-files)
- [Check unclosed resources](docs/index.md#check-unclosed-resources)
- [Garbage collection](docs/index.md#garbage-collection)
- [Custom handlers](docs/index.md#custom-handlers)
- [Subscribe events](docs/index.md#subscribe-events)
- [Advanced usage](docs/index.md#advanced-usage)

Read more about temp file on [Habr](https://habr.com/ru/post/320078/).
