# Documentation

- [Default configuration](#default-configuration)
- [Removing temp files](#removing-temp-files)
- [Check unclosed resources](#check-unclosed-resources)
- [Garbage collection](#garbage-collection)
- [Custom handlers](#custom-handlers)

## Default configuration

Configure TmpFileManager with config builder. By default temp files will purge automatically. Unclosed resources check and garbage collection are off. Below is the default configuration:

```php
<?php

use Bulletproof\TmpFileManager\ConfigBuilder;
use Bulletproof\TmpFileManager\TmpFileManager;

$config = (new ConfigBuilder())
    ->setTmpFileDirectory(sys_get_temp_dir())
    ->setTmpFilePrefix('php')
    ->setDeferredPurge(true)
    ->setUnclosedResourcesCheck(false)
    ->setGarbageCollectionProbability(0)
    ->setGarbageCollectionDivisor(100)
    ->setGarbageCollectionLifetime(3600)
    ->build()
;

$tmpFileManager = new TmpFileManager($config);
```
## Removing temp files

By default created temp files will purge automatically when PHP finished.

```php
<?php

use Bulletproof\TmpFile\TmpFile;
use Bulletproof\TmpFileManager\TmpFileManager;

$tmpFileManager = new TmpFileManager();

/** @var TmpFile $tmpFile */
$tmpFile = $tmpFileManager->createTmpFile();
```

You can remove temp files by manual with `removeTmpFile()`:

```php
$tmpFileManager->removeTmpFile($tmpFile);
```

If you need to purge all temp files by force call `purge()`:

```php
$tmpFileManager->purge();
```

In console commands use `createTmpFileContext()` method to create and handle temp files. Temp files will immediately removed after finished callback:

```php
$tmpFileManager->createTmpFileContext(function (TmpFile $tmpFile) {
    // ...
});
```

## Check unclosed resources

TmpFileManager can close open resources automatically before purge temp files. Configure `setUnclosedResourcesCheck()` to true.

```php
<?php

use Bulletproof\TmpFileManager\ConfigBuilder;
use Bulletproof\TmpFileManager\TmpFileManager;
use Bulletproof\TmpFile\TmpFile;

$config = (new ConfigBuilder())
    ->setUnclosedResourcesCheck(true)
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
```

## Garbage collection

The probability is calculated by using probability/divisor, e.g. 1/100 means there is a 1% chance that the garbage collection process start. *Lifetime* is seconds after which temp files will be seen as garbage and potentially cleaned up. 

```php
<?php

use Bulletproof\TmpFileManager\ConfigBuilder;
use Bulletproof\TmpFileManager\TmpFileManager;

$config = (new ConfigBuilder())
    ->setGarbageCollectionProbability(1)
    ->setGarbageCollectionDivisor(100)
    ->setGarbageCollectionLifetime(3600)
    ->build()
;
```

Garbage collector process will start before new the instance of TmpFileManager:

```php
$tmpFileManager = new TmpFileManager($config);
```

Also you can start garbage collection process with only handler:

```php
$handler = $config->getGarbageCollectionHandler();

$handler($config);
```

## Custom handlers

Define your handlers to get more control for manage temp files.

```php
<?php

use Bulletproof\TmpFileManager\ConfigBuilder;
use Bulletproof\TmpFileManager\DeferredPurgeHandler\DeferredPurgeHandler;
use Bulletproof\TmpFileManager\UnclosedResourcesHandler\UnclosedResourcesHandler;
use Bulletproof\TmpFileManager\GarbageCollectionHandler\GarbageCollectionHandler;

$config = (new ConfigBuilder())
    ->setDeferredPurgeHandler(new DeferredPurgeHandler())
    ->setUnclosedResourcesHandler(new UnclosedResourcesHandler())
    ->setGarbageCollectionHandler(new GarbageCollectionHandler())
    ->build()
;
```