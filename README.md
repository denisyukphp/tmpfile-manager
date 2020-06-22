# TmpFileManager

[![Build Status](https://travis-ci.org/denisyukphp/tmpfile-manager.svg?branch=master)](https://travis-ci.org/denisyukphp/tmpfile-manager) [![Total Downloads](https://poser.pugx.org/denisyukphp/tmpfile-manager/downloads)](https://packagist.org/packages/denisyukphp/tmpfile-manager) [![License](https://poser.pugx.org/denisyukphp/tmpfile-manager/license)](https://packagist.org/packages/denisyukphp/tmpfile-manager)

```
composer require denisyukphp/tmpfile-manager
```

This package requires PHP 7.1 or later.

## Quick usage

```php
<?php

use TmpFile\TmpFile;
use TmpFileManager\TmpFileManager;
use TmpFileManager\ConfigBuilder;

$config = (new ConfigBuilder())
    ->setTmpFileDirectory(sys_get_temp_dir())
    ->setTmpFilePrefix('php')
    ->build()
;

$tmpFileManager = new TmpFileManager($config);

/** @var TmpFile $tmpFile */
$tmpFile = $tmpFileManager->createTmpFile();
```

Further docs are available:

- [Default configuration](docs/index.md#default-configuration)
- [Removing temp files](docs/index.md#removing-temp-files)
- [Check unclosed resources](docs/index.md#check-unclosed-resources)
- [Garbage collection](docs/index.md#garbage-collection)
- [Custom handlers](docs/index.md#custom-handlers)
- [Subscribe events](docs/index.md#subscribe-events)
- [Advanced usage](docs/index.md#advanced-usage)
