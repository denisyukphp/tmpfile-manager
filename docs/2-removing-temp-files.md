# Removing temp files

By default temp files will be purge automatically if `setDeferredPurge()` is `true`.

```php
<?php

use Bulletproof\TmpFile\TmpFile;
use Bulletproof\TmpFileManager\TmpFileManager;
use Bulletproof\TmpFileManager\ConfigBuilder;
use Bulletproof\TmpFileManager\DeferredPurgeHandler\DeferredPurgeHandler;

$config = (new ConfigBuilder())
    ->setDeferredPurge(true)
    ->setDeferredPurgeHandler(new DeferredPurgeHandler())
    ->build()
;

$tmpFileManager = new TmpFileManager();

/** @var TmpFile $tmpFile */
$tmpFile = $tmpFileManager->createTmpFile();
```

You can remove temp files by manual with `removeTmpFile()` method:

```
$tmpFileManager->removeTmpFile($tmpFile);
```

If you need to purge all temp files by force from manager call `purge()`:

```php
$tmpFileManager->purge();
```