# Documentation

- [Default configuration](#default-configuration)
- [Creating temp files](#creating-temp-files)
- [Removing temp files](#removing-temp-files)
- [Check unclosed resources](#check-unclosed-resources)
- [Garbage collection](#garbage-collection)
- [Custom handlers](#custom-handlers)
- [Subscribe events](#subscribe-events)

## Default configuration

By default, temp files will purge automatically, unclosed resources check and garbage collection are off. Below is the default configuration:

```php
<?php

use TmpFileManager\Config\Config;
use TmpFileManager\TmpFileManager;

$config = new Config(
    tmpFileDirectory: sys_get_temp_dir(),
    tmpFilePrefix: 'php',
    isDeferredPurge: true,
    isUnclosedResourcesCheck: false,
    garbageCollectionProbability: 0,
    garbageCollectionDivisor: 100,
    garbageCollectionLifetime: 3600,
);

$tmpFileManager = new TmpFileManager($config);
```

You can use TmpFileManager with default options.

## Creating temp files

To create a temp file use `create()` method:

```php
<?php

use TmpFileManager\TmpFileManager;
use TmpFile\TmpFileInterface;

$tmpFileManager = new TmpFileManager();

/** @var TmpFileInterface $tmpFile */
$tmpFile = $tmpFileManager->create();
```

In console commands use `isolate()` method to create and handle temp files. Temp files will be immediately removed after finished callback:

```php
$tmpFileManager->isolate(function (TmpFileInterface $tmpFile) {
    // ...
});
```

Use one of these ways to create the temp file for your tasks.

## Removing temp files

By default, created temp files will purge automatically after PHP is finished, but you can remove temp files manually with `remove()` method:

```php
<?php

use TmpFileManager\TmpFileManager;
use TmpFile\TmpFileInterface;

$tmpFileManager = new TmpFileManager();

/** @var TmpFileInterface $tmpFile */
$tmpFileManager->create();

// ...

$tmpFileManager->remove($tmpFile);
```

If you need to purge all temp files by force get call `purge()` method:

```php
$tmpFileManager->purge();
```

All temp files will immediately remove.

## Check unclosed resources

TmpFileManager close open resources automatically before purging temp files:

```php
<?php

use TmpFileManager\Config\Config;
use TmpFileManager\TmpFileManager;
use TmpFile\TmpFileInterface;

$config = new Config(
    isUnclosedResourcesCheck: true,
);

$tmpFileManager = new TmpFileManager($config);

/** @var TmpFileInterface $tmpFile */
$tmpFile = $tmpFileManager->create();
    
$fh = fopen($tmpFile->getFilename(), 'r+');
    
fwrite($fh, random_bytes(1024));

// ...
```

After that you can ignore to open resources for temp files.

## Garbage collection

The probability is calculated by using probability/divisor, e.g. 1/100 means there is a 1% chance that the garbage collection process will start. Lifetime is seconds after which temp files will be seen as garbage and potentially cleaned up: 

```php
<?php

use TmpFileManager\Config\Config;
use TmpFileManager\Handler\GarbageCollectionHandler;
use TmpFileManager\TmpFileManager;

$config = new Config(
    garbageCollectionProbability: 1,
    garbageCollectionDivisor: 100,
    garbageCollectionLifetime: 3600,
);
```

Garbage collection process will start before an instance of TmpFileManager:

```php
$tmpFileManager = new TmpFileManager($config);
```

Also, you can start garbage collection process only with handler:

```php
$garbageCollectionHandler = new GarbageCollectionHandler();

$garbageCollectionHandler->handle($config);
```

By default, garbage collection is off.

## Custom handlers

Define your handlers to get more control of temp files. Each handler implements its own interface. Below are the default handlers:

```php
<?php

use TmpFileManager\Config\Config;
use TmpFileManager\Handler\DeferredPurgeHandler\DeferredPurgeHandler;
use TmpFileManager\Handler\UnclosedResourcesHandler\UnclosedResourcesHandler;
use TmpFileManager\Handler\GarbageCollectionHandler\GarbageCollectionHandler;

$config = new Config(
    deferredPurgeHandler: new DeferredPurgeHandler(),
    unclosedResourcesHandler: new UnclosedResourcesHandler(),
    garbageCollectionHandler: new GarbageCollectionHandler(),
);
```

`DeferredPurgeHandlerInterface::class` is needed to implement temp files purge after PHP is finished:

```php
<?php

use TmpFileManager\Handler\DeferredPurgeHandler\DeferredPurgeHandlerInterface;
use TmpFileManager\TmpFileManagerInterface;

class DeferredPurgeHandler implements DeferredPurgeHandlerInterface
{
    public function handle(TmpFileManagerInterface $tmpFileManager): void
    {
        // ...
    }
}
```

`UnclosedResourcesHandlerInterface::class` is handled unclosed resources of temp files:

```php
<?php

use TmpFileManager\Handler\UnclosedResourcesHandler\UnclosedResourcesHandlerInterface;
use TmpFileManager\Container\ContainerInterface;
use TmpFile\TmpFileInterface;

class UnclosedResourcesHandler implements UnclosedResourcesHandlerInterface
{
    public function handle(ContainerInterface $container): void
    {
        /** @var TmpFileInterface[] $tmpFiles */
        $tmpFiles = $container->getTmpFiles();
        
        // ...
    }
}
```

`GarbageCollectionHandlerInterface::class` is handled process of garbage collection: 

```php
<?php

use TmpFileManager\Handler\GarbageCollectionHandler\GarbageCollectionHandlerInterface;
use TmpFileManager\Config\ConfigInterface;

class GarbageCollectionHandler implements GarbageCollectionHandlerInterface
{
    public function handle(ConfigInterface $config): void
    {
        // ...
    }
}
```

Use handlers to pass specific logic or rewrite currents.

## Subscribe events

Subscribe to events to inject your code in lifecycle of temp files:

```php
<?php

use TmpFileManager\Event\TmpFileManagerStartEvent;
use TmpFileManager\Event\TmpFileCreateEvent;
use TmpFileManager\Event\TmpFileRemoveEvent;
use TmpFileManager\Event\TmpFileManagerPurgeEvent;
use TmpFileManager\TmpFileManagerInterface;
use TmpFileManager\Config\ConfigInterface;
use TmpFileManager\Container\ContainerInterface;
use TmpFileManager\Filesystem\FilesystemInterface;
use TmpFile\TmpFileInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;

$eventDispatcher = new EventDispatcher();
```

`TmpFileManagerStartEvent::class` is fired when TmpFileManager is constructed:

```php
$eventDispatcher->addListener(TmpFileManagerStartEvent::class, function (TmpFileManagerStartEvent $event): void {
    /** @var TmpFileManagerInterface $tmpFileManager */
    $tmpFileManager = $event->tmpFileManage;
    /** @var ConfigInterface $config */
    $config = $event->config;
    /** @var ContainerInterface $container */
    $container = $event->container;
    /** @var FilesystemInterface $filesystem */
    $filesystem = $event->filesystem;
    
    // ...
});
```

`TmpFileCreateEvent::class` is fired after a temp file created:

```php
$eventDispatcher->addListener(TmpFileCreateEvent::class, function (TmpFileCreateEvent $event): void {
    /** @var TmpFileInterface $tmpFile */
    $tmpFile = $event->tmpFile;
    
    // ...
});
```

`TmpFileRemoveEvent::class` is fired before the temp file removed:

```php
$eventDispatcher->addListener(TmpFileRemoveEvent::class, function (TmpFileRemoveEvent $event): void {
    /** @var TmpFileInterface $tmpFile */
    $tmpFile = $event->tmpFile;
    
    // ...
});
```

`TmpFileManagerPurgeEvent::class` is fired before all temp files will are purge:

```php
$eventDispatcher->addListener(TmpFileManagerPurgeEvent::class, function (TmpFileManagerPurgeEvent $event): void {
    /** @var TmpFileManagerInterface $tmpFileManager */
    $tmpFileManager = $event->tmpFileManage;
    /** @var ConfigInterface $config */
    $config = $event->config;
    /** @var ContainerInterface $container */
    $container = $event->container;
    /** @var FilesystemInterface $filesystem */
    $filesystem = $event->filesystem;
    
    // ...
});
```

After that you need to add event dispatcher to TmpFileManager to your event listeners run fire.

```php
$tmpFileManager = new TmpFileManager(
    eventDispatcher: $eventDispatcher,
);
```

This allows flexible management.
