# TmpFileManager\TmpFileManager

[![Build Status](https://travis-ci.org/denisyukphp/tmpfile-manager.svg?branch=master)](https://travis-ci.org/denisyukphp/tmpfile-manager) [![Total Downloads](https://poser.pugx.org/denisyukphp/tmpfile-manager/downloads)](https://packagist.org/packages/denisyukphp/tmpfile-manager) [![License](https://poser.pugx.org/denisyukphp/tmpfile-manager/license)](https://packagist.org/packages/denisyukphp/tmpfile-manager)

```
composer require denisyukphp/tmpfile-manager ^2
```

This package requires PHP 7.1 or later.

## Quick usage

```php
<?php

use TmpFile\TmpFile;
use TmpFileManager\Config;
use TmpFileManager\ConfigBuilder;
use TmpFileManager\TmpFileManager;

/** @var Config $config */
$config = (new ConfigBuilder())
    ->setTmpFileDirectory('/tmp')
    ->setTmpFilePrefix('php')
    ->setCheckUnclosedResources(true)
    ->build()
;

$tmpFileManager = new TmpFileManager($config);

for ($i = 0; $i < 10; $i++) {
    /** @var TmpFile $tmpFile */
    $tmpFile = $tmpFileManager->createTmpFile();

    $fh = fopen($tmpFile, 'r+');

    fwrite($fh, random_bytes(1024));

    // ...
}

$tmpFileManager->createTmpFileContext(function (TmpFile $tmpFile) {
    file_put_contents($tmpFile, 'Meow!');

    rename($tmpFile, '/path/to/meow.txt');
});
```

You can read more about temp file on [Habr](https://habr.com/ru/post/320078/).
