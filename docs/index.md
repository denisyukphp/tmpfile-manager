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

```text
+--------------------------+--------------------+
| Option                   | Default            |
+--------------------------+--------------------+
| temp file dir            | sys_get_temp_dir() |
| temp file prefix         | php                |
| check unclosed resources | [x]                |
| garbage collection       | [x]                |
+--------------------------+--------------------+
```

To create a temp file manager, build it with options like below:

```php
<?php

use TmpFileManager\TmpFileManagerBuilder;

$tmpFileManager = (new TmpFileManagerBuilder())
    ->withTmpFileDir(sys_get_temp_dir())
    ->withTmpFilePrefix('php')
    ->build()
;
```

All options are not required.

## Creating temp files

To create a temporary file, use the `TmpFileManager\TmpFileManagerInterface::create(): TmpFile\TmpFileInterface` method. All created temp files are added on the stack and will be removed after PHP is finished:

```php
/** @var TmpFile\TmpFileInterface $tmpFile */
$tmpFile = $tmpFileManager->create();
```

In console commands, use the `TmpFileManager\TmpFileManagerInterface::isolate(TmpFile\TmpFileInterface $tmpFile): void` method to create and handle temp files. Temp files will be immediately removed after the finished callback:
```php
$tmpFileManager->isolate(function (TmpFile\TmpFileInterface $tmpFile): void {
    // ...
});
```

Use one of the following methods to create a temp files for your tasks properly.

## Loading temp files

To add existing temp files to the temp file manager, use the `TmpFileManager\TmpFileManagerInterface::load(TmpFile\TmpFileInterface ...$tmpFiles): void` method:

```php
$tmpFiles = [
    new TmpFileManager\TmpFile(__DIR__.'/cat.jpg'),
    new TmpFileManager\TmpFile(__DIR__.'/dog.jpg'),
    new TmpFileManager\TmpFile(__DIR__.'/fish.jpg'),
];

$tmpFileManager->load(...$tmpFiles);
```

The source files will be removed after PHP is finished.

## Removing temp files

By default, created temp files will purge automatically after PHP is finished, but you can remove temp files manually with `TmpFileManager\TmpFileManagerInterface::remove(TmpFile\TmpFileInterface $tmpFile): void` method:

```php
$tmpFileManager->remove($tmpFile);
```

If you need to forcefully purge all temp files, you can use the `TmpFileManager\TmpFileManagerInterface::purge(): void` method:

```php
$tmpFileManager->purge();
```

All temp files will be immediately removed.

## Check unclosed resources

Before purging temp files temp file manager can check unclosed resources and close opened resources which refer to temp files. Build temp file manager with unclosed resources' handler like below:

```php
<?php

use TmpFileManager\Handler\UnclosedResourcesHandler\UnclosedResourcesHandler;
use TmpFileManager\TmpFileManagerBuilder;

$tmpFileManager = (new TmpFileManagerBuilder())
    ->withUnclosedResourcesHandler(new UnclosedResourcesHandler())
    ->build()
;
```

Now you can ignore opened resources, e. g.:

```php
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
use TmpFileManager\Handler\GarbageCollectionHandler\Processor\AsyncProcessor;
use TmpFileManager\TmpFileManagerBuilder;

$garbageCollectionHandler = new GarbageCollectionHandler(
    probability: 1,
    divisor: 100,
    lifetime: 3600,
    processor: new AsyncProcessor(),
);

$tmpFileManager = (new TmpFileManagerBuilder())
    ->withGarbageCollectionHandler($garbageCollectionHandler)
    ->build()
;
```

Choose sync or async processor to make memory consumption more efficient:

- [TmpFileManager\Handler\GarbageCollectionHandler\Processor\AsyncProcessor](../src/Handler/GarbageCollectionHandler/Processor/AsyncProcessor.php)
- [TmpFileManager\Handler\GarbageCollectionHandler\Processor\SyncProcessor](../src/Handler/GarbageCollectionHandler/Processor/SyncProcessor.php)

By default, garbage collection is off.

## Lifecycle events

The temp file manager is based on events. All handlers are implemented through events. Use them to put your own code into the temp file's lifecycle:

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

- [TmpFileManager\Event\TmpFileManagerOnStart](../src/Event/TmpFileManagerOnStart.php)
- [TmpFileManager\Event\TmpFileManagerPreCreate](../src/Event/TmpFileManagerPreCreate.php)
- [TmpFileManager\Event\TmpFileManagerPostCreate](../src/Event/TmpFileManagerPostCreate.php)
- [TmpFileManager\Event\TmpFileManagerPreLoad](../src/Event/TmpFileManagerPreLoad.php)
- [TmpFileManager\Event\TmpFileManagerPostLoad](../src/Event/TmpFileManagerPostLoad.php)
- [TmpFileManager\Event\TmpFileManagerPrePurge](../src/Event/TmpFileManagerPrePurge.php)
- [TmpFileManager\Event\TmpFileManagerPostPurge](../src/Event/TmpFileManagerPostPurge.php)
- [TmpFileManager\Event\TmpFileManagerOnFinish](../src/Event/TmpFileManagerOnFinish.php)

Events related to temp file:

- [TmpFileManager\Event\TmpFileOnCreate](../src/Event/TmpFileOnCreate.php)
- [TmpFileManager\Event\TmpFileOnLoad](../src/Event/TmpFileOnLoad.php)
- [TmpFileManager\Event\TmpFilePreRemove](../src/Event/TmpFilePreRemove.php)
- [TmpFileManager\Event\TmpFilePostRemove](../src/Event/TmpFilePostRemove.php)

To register an event listener, use the `TmpFileManager\TmpFileManagerBuilder::withEventListener(string $eventName, callable $listenerCallback): self` method in the builder:

```php
<?php

use TmpFileManager\TmpFileManagerBuilder;
use TmpFileManager\Event\TmpFileOnCreate;

$tmpFileManager = (new TmpFileManagerBuilder())
    ->withEventListener(
        TmpFileOnCreate::class,
        static function (TmpFileOnCreate $tmpFileOnCreate): void {
            // ...
        },
    )
    ->build()
;

$tmpFile = $tmpFileManager->create();
```

All listeners will be run when the temp file manager fires events.
