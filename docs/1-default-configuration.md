# Default configuration

By default temp files will be purge automatically. Unclosed resources check and garbage collection are off. You can configure TmpFileManager with config builder. Below is the default configuration:

```php
<?php

use Bulletproof\TmpFileManager\TmpFileManager;
use Bulletproof\TmpFileManager\ConfigBuilder;
use Bulletproof\TmpFileManager\DeferredPurgeHandler\DeferredPurgeHandler;
use Bulletproof\TmpFileManager\UnclosedResourcesHandler\UnclosedResourcesHandler;
use Bulletproof\TmpFileManager\GarbageCollectionHandler\GarbageCollectionHandler;

$config = (new ConfigBuilder())
    ->setTmpFileDirectory(sys_get_temp_dir())
    ->setTmpFilePrefix('php')
    ->setDeferredPurge(true)
    ->setDeferredPurgeHandler(new DeferredPurgeHandler())
    ->setUnclosedResourcesCheck(false)
    ->setUnclosedResourcesHandler(new UnclosedResourcesHandler())
    ->setGarbageCollectionProbability(0)
    ->setGarbageCollectionDivisor(100)
    ->setGarbageCollectionLifetime(3600)
    ->setGarbageCollectionHandler(new GarbageCollectionHandler())
    ->build()
;

$tmpFileManager = new TmpFileManager($config);
```