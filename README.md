# TmpFileManager

[![Build Status](https://img.shields.io/github/actions/workflow/status/denisyukphp/tmpfile-manager/ci.yml?branch=master&style=plastic)](https://github.com/denisyukphp/tmpfile-manager/actions/workflows/ci.yml)
[![Latest Stable Version](https://img.shields.io/packagist/v/denisyukphp/tmpfile-manager?style=plastic)](https://packagist.org/packages/denisyukphp/tmpfile-manager)
[![Packagist PHP Version Support](https://img.shields.io/packagist/php-v/denisyukphp/tmpfile-manager?style=plastic&color=8892BF)](https://packagist.org/packages/denisyukphp/tmpfile-manager)
[![Total Downloads](https://img.shields.io/packagist/dt/denisyukphp/tmpfile-manager?style=plastic)](https://packagist.org/packages/denisyukphp/tmpfile-manager)
[![License](https://img.shields.io/packagist/l/denisyukphp/tmpfile-manager?style=plastic&color=428F7E)](https://packagist.org/packages/denisyukphp/tmpfile-manager)

Temp file manager.

## Installation

You can install the latest version via [Composer](https://getcomposer.org/):

```text
composer require denisyukphp/tmpfile-manager
```

This package requires PHP 8.0 or later.

## Quick usage

Build a temp file manager and create a temp file:

```php
<?php

use TmpFileManager\TmpFileManagerBuilder;
use TmpFile\TmpFileInterface;

$tmpFileManager = (new TmpFileManagerBuilder())
    ->withTmpFileDir(sys_get_temp_dir())
    ->withTmpFilePrefix('php')
    ->build()
;

/** @var TmpFileInterface $tmpFile */
$tmpFile = $tmpFileManager->create();
```

All temp files created the manager will be purged automatically by default.

## Documentation

- [Default configuration](docs/index.md#default-configuration)
- [Creating temp files](docs/index.md#creating-temp-files)
- [Loading temp files](docs/index.md#loading-temp-files)
- [Removing temp files](docs/index.md#removing-temp-files)
- [Check unclosed resources](docs/index.md#check-unclosed-resources)
- [Garbage collection](docs/index.md#garbage-collection)
- [Lifecycle events](docs/index.md#lifecycle-events)

Read more about temp file on [Habr](https://habr.com/ru/post/320078/).
