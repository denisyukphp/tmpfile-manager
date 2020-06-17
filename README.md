# TmpFileManager

[![Build Status](https://travis-ci.org/denisyukphp/tmpfile-manager.svg?branch=master)](https://travis-ci.org/denisyukphp/tmpfile-manager) [![Total Downloads](https://poser.pugx.org/denisyukphp/tmpfile-manager/downloads)](https://packagist.org/packages/denisyukphp/tmpfile-manager) [![License](https://poser.pugx.org/denisyukphp/tmpfile-manager/license)](https://packagist.org/packages/denisyukphp/tmpfile-manager)

```
composer require denisyukphp/tmpfile-manager
```

This package requires PHP 7.1 or later.

## Quick start

```php
<?php

use Bulletproof\TmpFile\TmpFile;
use Bulletproof\TmpFileManager\TmpFileManager;

$tmpFileManager = new TmpFileManager();

/** @var TmpFile $tmpFile */
$tmpFile = $tmpFileManager->createTmpFile();

$tmpFileManager->createTmpFileContext(function (TmpFile $tmpFile) {
    // temp file will be removed after finished callback
});

$tmpFileManager->purge();
```
