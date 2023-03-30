# Documentation

- [Default configuration](#default-configuration)
- [Creating temp files](#creating-temp-files)
- [Loading temp files](#loading-temp-files)
- [Removing temp files](#removing-temp-files)
- [Check unclosed resources](#check-unclosed-resources)
- [Garbage collection](#garbage-collection)
- [Lifecycle events](#lifecycle-events)

## Default configuration

By default, temp files purge automatically, check unclosed resources and garbage collection are off. Below is the default configuration:

```php
<?php

use TmpFileManager\TmpFileManagerBuilder;

$tmpFileManager = (new TmpFileManagerBuilder())
    ->withTmpFileDirectory(sys_get_temp_dir())
    ->withTmpFilePrefix('php')
    ->build()
;
```

[...]

## Creating temp files

[...]

```php
<?php

use TmpFileManager\TmpFileManagerBuilder;

$tmpFileManager = (new TmpFileManagerBuilder())
    ->withTmpFileDirectory(sys_get_temp_dir())
    ->withTmpFilePrefix('php')
    ->build()
;
```

[...]

```php
$tmpFile = $tmpFileManager->create();
```


[...]

```php
$tmpFileManager->isolate(function (TmpFileInterface $tmpFile): void {
    // ...
});
```

[...]

## Loading temp files

[...]

```php
<?php

use TmpFileManager\TmpFileManagerBuilder;

$tmpFileManager = (new TmpFileManagerBuilder())
    ->withTmpFileDirectory(sys_get_temp_dir())
    ->withTmpFilePrefix('php')
    ->build()
;

$files = [
    new \SplFileInfo(__DIR__.'/cat.jpg'),
    new \SplFileInfo(__DIR__.'/dog.jpg'),
    new \SplFileInfo(__DIR__.'/fish.jpg'),
];

$tmpFileManager->load(...$files);
```

[...]

## Removing temp files

[...]

```php
<?php

use TmpFileManager\TmpFileManagerBuilder;

$tmpFileManager = (new TmpFileManagerBuilder())
    ->withTmpFileDirectory(sys_get_temp_dir())
    ->withTmpFilePrefix('php')
    ->build()
;

$tmpFile = $tmpFileManager->create();
```

[...]

```php
$tmpFileManager->remove($tmpFile);
```

[...]

```php
$tmpFileManager->purge();
```

[...]

## Check unclosed resources

[...]

```php
<?php

use TmpFileManager\Handler\OpenResourcesHandler\OpenResourcesHandler;
use TmpFileManager\TmpFileManagerBuilder;

$tmpFileManager = (new TmpFileManagerBuilder())
    ->withOpenResourcesHandler(new OpenResourcesHandler())
    ->build()
;

$tmpFile = $tmpFileManager->create();
$fh = fopen($tmpFile->getFilename(), 'r+');
fwrite($fh, random_bytes(1024));

// ...
```

By default, check unclosed resources is off.

## Garbage collection

Garbage collection process starts after temp files purging. The probability is calculated by using probability/divisor, e. g. 1/100 means there is a 1% chance that the garbage collection process will start. Lifetime is seconds after which temp files will be seen as garbage and potentially cleaned up:

```php
<?php

use TmpFileManager\Handler\GarbageCollectionHandler\GarbageCollectionHandler;
use TmpFileManager\Handler\GarbageCollectionHandler\Processor\SyncProcessor;
use TmpFileManager\TmpFileManagerBuilder;

$garbageCollectionHandler = new GarbageCollectionHandler(
    probability: 1,
    divisor: 100,
    lifetime: 3600,
    processor: new SyncProcessor(),
);

$tmpFileManager = (new TmpFileManagerBuilder())
    ->withGarbageCollectionHandler($garbageCollectionHandler)
    ->build()
;
```

Choose sync or async processor to make memory consumption more efficient:

- [AsyncProcessor](../src/Handler/GarbageCollectionHandler/Processor/AsyncProcessor.php)
- [SyncProcessor](../src/Handler/GarbageCollectionHandler/Processor/SyncProcessor.php)

By default, garbage collection is off.

## Lifecycle events

Temp file manager is based on events. All handlers implemented through events. Use them to put your own code in temp file's lifecycle:

```text
          ┌─────────────┐
          │ onStart     │
          └──────┬──────┘
       ┌─────────┴─────────┐
       ▼                   ▼
┌─────────────┐     ┌─────────────┐
│ preCreate   │     │ preLoad     │
│ onCreate    │     │ onLoad      │
│ postCreate  │     │ postLoad    │
└──────┬──────┘     └──────┬──────┘
       └─────────┬─────────┘
                 ▼
          ┌─────────────┐
          │ preRemove   │
          │ postRemove  │
          └──────┬──────┘
                 ▼
          ┌─────────────┐
          │ prePurge    │
          │ postPurge   │
          └──────┬──────┘
                 ▼
          ┌─────────────┐
          │ onFinish    │
          └─────────────┘
```

Events related to temp file manager:

- [TmpFileManagerOnStart](../src/Event/TmpFileManagerOnStart.php)
- [TmpFileManagerPreCreate](../src/Event/TmpFileManagerPreCreate.php)
- [TmpFileManagerPostCreate](../src/Event/TmpFileManagerPostCreate.php)
- [TmpFileManagerPreLoad](../src/Event/TmpFileManagerPreLoad.php)
- [TmpFileManagerPostLoad](../src/Event/TmpFileManagerPostLoad.php)
- [TmpFileManagerPrePurge](../src/Event/TmpFileManagerPrePurge.php)
- [TmpFileManagerPostPurge](../src/Event/TmpFileManagerPostPurge.php)
- [TmpFileManagerOnFinish](../src/Event/TmpFileManagerOnFinish.php)

Events related to temp file:

- [TmpFileOnCreate](../src/Event/TmpFileOnCreate.php)
- [TmpFileOnLoad](../src/Event/TmpFileOnLoad.php)
- [TmpFilePreRemove](../src/Event/TmpFilePreRemove.php)
- [TmpFilePostRemove](../src/Event/TmpFilePostRemove.php)

[...]

```php
<?php

use TmpFileManager\TmpFileManagerBuilder;
use TmpFileManager\Event\TmpFileOnCreate;

$tmpFileManager = (new TmpFileManagerBuilder())
    ->addEventListener(TmpFileOnCreate::class, static function (TmpFileOnCreate $tmpFileOnCreate): void {
        // ...
    })
    ->build()
;

$tmpFile = $tmpFileManager->create();
```

[...]
