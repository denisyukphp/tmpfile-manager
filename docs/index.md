# Documentation

- [Default configuration](#default-configuration)
- [Creating temp files](#creating-temp-files)
- [Removing temp files](#removing-temp-files)
- [Check unclosed resources](#check-unclosed-resources)
- [Garbage collection](#garbage-collection)
- [Custom handlers](#custom-handlers)
- [Subscribe events](#subscribe-events)
- [Advanced usage](#advanced-usage)

## Default configuration

By default temp files will purge automatically, unclosed resources check and garbage collection are off. Below is the default configuration:

```php
<?php

use TmpFileManager\Config\ConfigBuilder;
use TmpFileManager\Handler\DeferredPurgeHandler\DeferredPurgeHandler;
use TmpFileManager\Handler\UnclosedResourcesHandler\UnclosedResourcesHandler;
use TmpFileManager\Handler\GarbageCollectionHandler\GarbageCollectionHandler;
use TmpFileManager\TmpFileManager;

$config = ConfigBuilder::create()
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

You can use TmpFileManager with default options.

## Creating temp files

To create a temp file use `createTmpFile()` method:

```php
<?php

use TmpFile\TmpFileInterface;
use TmpFileManager\TmpFileManager;

$tmpFileManager = new TmpFileManager();

/** @var TmpFileInterface $tmpFile */
$tmpFile = $tmpFileManager->createTmpFile();
```

In console commands use `createTmpFileContext()` method to create and handle temp files. Temp files will be immediately removed after finished callback:

```php
$tmpFileManager->createTmpFileContext(function (TmpFile $tmpFile) {
    // ...
});
```

Use one of these ways to create the temp file for your tasks.

## Removing temp files

By default created temp files will purge automatically after PHP is finished but you can remove temp files manually with `removeTmpFile()` method:

```php
<?php

use TmpFile\TmpFileInterface;
use TmpFileManager\TmpFileManager;

$tmpFileManager = new TmpFileManager();

/** @var TmpFileInterface $tmpFile */
$tmpFileManager->createTmpFile();

// ...

$tmpFileManager->removeTmpFile($tmpFile);
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

use TmpFile\TmpFileInterface;
use TmpFileManager\Config\ConfigBuilder;
use TmpFileManager\TmpFileManager;

$config = ConfigBuilder::create()
    ->setUnclosedResourcesCheck(true)
    ->build()
;

$tmpFileManager = new TmpFileManager($config);

/** @var TmpFileInterface $tmpFile */
$tmpFile = $tmpFileManager->createTmpFile();
    
$fh = fopen($tmpFile, 'r+');
    
fwrite($fh, random_bytes(1024));

// ...
```

After that you can ignore to open resources for temp files.

## Garbage collection

The probability is calculated by using probability/divisor, e.g. 1/100 means there is a 1% chance that the garbage collection process will start. Lifetime is seconds after which temp files will be seen as garbage and potentially cleaned up: 

```php
<?php

use TmpFileManager\Config\ConfigBuilder;
use TmpFileManager\Handler\GarbageCollectionHandler;
use TmpFileManager\TmpFileManager;

$config = ConfigBuilder::create()
    ->setGarbageCollectionProbability(1)
    ->setGarbageCollectionDivisor(100)
    ->setGarbageCollectionLifetime(3600)
    ->build()
;
```

Garbage collection process will start before an instance of TmpFileManager:

```php
$tmpFileManager = new TmpFileManager($config);
```

Also you can start garbage collection process only with handler:

```php
$handler = new GarbageCollectionHandler();

$handler->handle($config);
```

By default garbage collection is off.

## Custom handlers

Define your handlers to get more control of temp files. Each handler implements its own interface. Below are the default handlers:

```php
<?php

use TmpFileManager\Config\ConfigBuilder;
use TmpFileManager\Handler\DeferredPurgeHandler\DeferredPurgeHandler;
use TmpFileManager\Handler\UnclosedResourcesHandler\UnclosedResourcesHandler;
use TmpFileManager\Handler\GarbageCollectionHandler\GarbageCollectionHandler;

$config = ConfigBuilder::create()
    ->setDeferredPurgeHandler(new DeferredPurgeHandler())
    ->setUnclosedResourcesHandler(new UnclosedResourcesHandler())
    ->setGarbageCollectionHandler(new GarbageCollectionHandler())
    ->build()
;
```

`DeferredPurgeHandlerInterface::class` is needed to implement temp files purge after PHP is finished:

```php
<?php

use TmpFileManager\TmpFileManagerInterface;
use TmpFileManager\Handler\DeferredPurgeHandler\DeferredPurgeHandlerInterface;

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

use TmpFile\TmpFileInterface;
use TmpFileManager\Container\ContainerInterface;
use TmpFileManager\Handler\UnclosedResourcesHandler\UnclosedResourcesHandlerInterface;

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

`GarbageCollectionHandlerInterface::class` is handled process of garbage collection : 

```php
<?php

use TmpFileManager\Config\ConfigInterface;
use TmpFileManager\Handler\GarbageCollectionHandler\GarbageCollectionHandlerInterface;

class GarbageCollectionHandler implements GarbageCollectionHandlerInterface
{
    public function handle(ConfigInterface $config): void
    {
        // ...
    }
}
```

Use handlers to pass specific logic.

## Subscribe events

Subscribe to events to inject your code in lifecycle of temp files:

```php
<?php

use TmpFile\TmpFileInterface;
use TmpFileManager\Event\TmpFileManagerStartEvent;
use TmpFileManager\Event\TmpFileCreateEvent;
use TmpFileManager\Event\TmpFileRemoveEvent;
use TmpFileManager\Event\TmpFileManagerPurgeEvent;
use TmpFileManager\TmpFileManager;
use Symfony\Component\EventDispatcher\EventDispatcher;

$eventDispatcher = new EventDispatcher();
```

`TmpFileManagerStartEvent::class` is fired when TmpFileManager is constructed:

```php
$eventDispatcher->addListener(TmpFileManagerStartEvent::class, function (TmpFileManagerStartEvent $startEvent) {
    /** @var TmpFileManager $tmpFileManager */
    $tmpFileManager = $startEvent->getTmpFileManager();
    
    // ...
});
```

`TmpFileCreateEvent::class` is fired after a temp file created:

```php
$eventDispatcher->addListener(TmpFileCreateEvent::class, function (TmpFileCreateEvent $createEvent) {
    /** @var TmpFileInterface $tmpFile */
    $tmpFile = $createEvent->getTmpFile();
    
    // ...
});
```

`TmpFileRemoveEvent::class` is fired before the temp file removed:

```php
$eventDispatcher->addListener(TmpFileRemoveEvent::class, function (TmpFileRemoveEvent $removeEvent) {
    /** @var TmpFileInterface $tmpFile */
    $tmpFile = $removeEvent->getTmpFile();
    
    // ...
});
```

`TmpFileManagerPurgeEvent::class` is fired before all temp files will are purge:

```php
$eventDispatcher->addListener(TmpFileManagerPurgeEvent::class, function (TmpFileManagerPurgeEvent $purgeEvent) {
    /** @var TmpFileManager $tmpFileManager */
    $tmpFileManager = $purgeEvent->getTmpFileManager();
    
    // ...
});
```

After that you need to add event dispatcher to TmpFileManager to your event listeners run fire.

```php
/** @var \TmpFileManager\Config\ConfigInterface $config */
/** @var \TmpFileManager\Container\ContainerInterface $container */
/** @var \TmpFileManager\Filesystem\FilesystemInterface $filesystem */
/** @var \Symfony\Component\EventDispatcher\EventDispatcher $eventDispatcher */
/** @var \TmpFileManager\Provider\ProviderInterface $provider */

$tmpFileManager = new TmpFileManager($config, $container, $filesystem, $eventDispatcher, $provider);
```

This allows flexible management.

## Advanced usage

To get more control over temp files, implement service interfaces or use exists:

```php
<?php

use TmpFileManager\Config\Config;
use TmpFileManager\Config\ConfigInterface;
use TmpFileManager\Container\Container;
use TmpFileManager\Container\ContainerInterface;
use TmpFileManager\Filesystem\Filesystem;
use TmpFileManager\Filesystem\FilesystemInterface;
use TmpFileManager\Provider\ReflectionProvider;
use TmpFileManager\Provider\ProviderInterface;
use TmpFileManager\TmpFileManager;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/** @var ConfigInterface $config */
$config = Config::default();

/** @var ContainerInterface $container */
$container = new Container();

/** @var FilesystemInterface $filesystem */
$filesystem = Filesystem::create();

/** @var EventDispatcherInterface $eventDispatcher */
$eventDispatcher = new EventDispatcher();

/** @var ProviderInterface $provider */
$provider = new ReflectionProvider();
```

For example you have opportunity to store temp files in cloud or change way of configuration. Inject your dependencies:

```php
$tmpFileManager = new TmpFileManager($config, $container, $filesystem, $eventDispatcher, $provider);

/** @var ConfigInterface $config */
$config = $tmpFileManager->getConfig();

/** @var ContainerInterface $container */
$container = $tmpFileManager->getContainer();

/** @var FilesystemInterface $filesystem */
$filesystem = $tmpFileManager->getFilesystem();

/** @var EventDispatcherInterface $eventDispatcher */
$eventDispatcher = $tmpFileManager->getEventDispatcher();

/** @var ProviderInterface $provider */
$provider = $tmpFileManager->getProvider();
```

After that services are available to extend use-cases in any part your application.
