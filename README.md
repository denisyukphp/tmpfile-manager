# TmpFileManager

[![Build Status](https://travis-ci.org/denisyukphp/tmpfile-manager.svg?branch=master)](https://travis-ci.org/denisyukphp/tmpfile-manager) [![Total Downloads](https://poser.pugx.org/denisyukphp/tmpfile-manager/downloads)](https://packagist.org/packages/denisyukphp/tmpfile-manager) [![License](https://poser.pugx.org/denisyukphp/tmpfile-manager/license)](https://packagist.org/packages/denisyukphp/tmpfile-manager)

```
composer require denisyukphp/tmpfile-manager
```

This package requires PHP 7.1 or later.

## Quick usage

```php
<?php

use Bulletproof\TmpFile\TmpFile;
use Bulletproof\TmpFileManager\TmpFileManager;
use Bulletproof\TmpFileManager\ConfigBuilder;

$config = (new ConfigBuilder())
    ->setTmpFileDirectory(sys_get_temp_dir())
    ->setTmpFilePrefix('php')
    ->build()
;

$tmpFileManager = new TmpFileManager($config);

/** @var TmpFile $tmpFile */
$tmpFile = $tmpFileManager->createTmpFile();
```

Read the docs:

- [Default configuration](docs/1-default-configuration.md)
- [Removing temp files](docs/2-removing-temp-files.md)
- [Usage in CLI](docs/3-usage-in-cli.md)
- [Check unclosed resources](docs/4-check-unclosed-resources.md)
- [Garbage collection](docs/5-garbage-collection.md)