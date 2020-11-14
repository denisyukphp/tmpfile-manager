# Documentation

- [Default configuration](#default-configuration)
- [Removing temp files](#removing-temp-files)
- [Check unclosed resources](#check-unclosed-resources)
- [Garbage collection](#garbage-collection)
- [Custom handlers](#custom-handlers)
- [Subscribe events](#subscribe-events)
- [Advanced usage](#advanced-usage)

## Default configuration

Configure TmpFileManager with config builder. By default, temp files will purge automatically. Unclosed resources check and garbage collection are off. Below is the default configuration:

```php
<?php

use TmpFileManager\Config\ConfigBuilder;
use TmpFileManager\TmpFileManager;

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

By default, created temp files will purge automatically after PHP is finished.

```php
<?php

use TmpFile\TmpFile;
use TmpFileManager\TmpFileManager;

$tmpFileManager = new TmpFileManager();

/** @var TmpFile $tmpFile */
$tmpFile = $tmpFileManager->createTmpFile();
```

You can remove temp files manually with `removeTmpFile()`:

```php
$tmpFileManager->removeTmpFile($tmpFile);
```

If you need to purge all temp files by force call `purge()`:

```php
$tmpFileManager->purge();
```

In console commands use `createTmpFileContext()` method to create and handle temp files. Temp files will be immediately removed after finished callback:

```php
$tmpFileManager->createTmpFileContext(function (TmpFile $tmpFile) {
    // ...
});
```

## Check unclosed resources

TmpFileManager can close open resources automatically before purging temp files. Configure `setUnclosedResourcesCheck()` to `true`:

```php
<?php

use TmpFile\TmpFile;
use TmpFileManager\Config\ConfigBuilder;
use TmpFileManager\TmpFileManager;

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

The probability is calculated by using probability/divisor, e.g. 1/100 means there is a 1% chance that the garbage collection process will start. Lifetime is seconds after which temp files will be seen as garbage and potentially cleaned up. 

```php
<?php

use TmpFileManager\Config\ConfigBuilder;
use TmpFileManager\TmpFileManager;

$config = (new ConfigBuilder())
    ->setGarbageCollectionProbability(1)
    ->setGarbageCollectionDivisor(100)
    ->setGarbageCollectionLifetime(3600)
    ->build()
;
```

Garbage collection process will start before the new instance of TmpFileManager:

```php
$tmpFileManager = new TmpFileManager($config);
```

Also you can start garbage collection process only with handler:

```php
$handler = $config->getGarbageCollectionHandler();

$handler->handle($config);
```

## Custom handlers

Define your handlers to get more control of temp files management with config builder. Below are the default handlers:

```php
<?php

use TmpFileManager\Config\ConfigBuilder;
use TmpFileManager\Handler\DeferredPurgeHandler\DeferredPurgeHandler;
use TmpFileManager\Handler\UnclosedResourcesHandler\UnclosedResourcesHandler;
use TmpFileManager\Handler\GarbageCollectionHandler\GarbageCollectionHandler;

$config = (new ConfigBuilder())
    ->setDeferredPurgeHandler(new DeferredPurgeHandler())
    ->setUnclosedResourcesHandler(new UnclosedResourcesHandler())
    ->setGarbageCollectionHandler(new GarbageCollectionHandler())
    ->build()
;
```

Each handler implements its own interface. DeferredPurgeHandlerInterface is needed to implement temp files purge after PHP is finished. You can make your handler implementing this interface:

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

To replace unclosed resources handler yoo need to implement UnclosedResourcesHandlerInterface:

```php
<?php

use TmpFile\TmpFile;
use TmpFileManager\Handler\UnclosedResourcesHandler\UnclosedResourcesHandlerInterface;

class UnclosedResourcesHandler implements UnclosedResourcesHandlerInterface
{
    /**
     * @param TmpFile[] $tmpFiles
     */
    public function handle(array $tmpFiles): void
    {
        // ...
    }
}
```

Garbage collection process has its own handler GarbageCollectionHandlerInterface. Implement this interface to change garbage collection handler by default:

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

## Subscribe events

With EventDispatcher you can subscribe manager's events to inject your code in lifecycle of temp files.

```php
<?php

use TmpFile\TmpFile;
use TmpFileManager\Event\TmpFileManagerStartEvent;
use TmpFileManager\Event\TmpFileCreateEvent;
use TmpFileManager\Event\TmpFileRemoveEvent;
use TmpFileManager\Event\TmpFileManagerPurgeEvent;
use TmpFileManager\TmpFileManager;
use Symfony\Component\EventDispatcher\EventDispatcher;

$eventDispatcher = new EventDispatcher();
```

`TmpFileManagerStartEvent::class` is fired when TmpFileManager constructing. Here deferred purge handler and garbage collector are register. This event haven't access to temp files:

```php
$eventDispatcher->addListener(TmpFileManagerStartEvent::class, function (TmpFileManagerStartEvent $tmpFileManagerStartEvent) {
    /** @var TmpFileManager $tmpFileManager */
    $tmpFileManager = $tmpFileManagerStartEvent->getTmpFileManager();
    
    // ...
});
```

`TmpFileCreateEvent::class` is fired after temp file created:

```php
$eventDispatcher->addListener(TmpFileCreateEvent::class, function (TmpFileCreateEvent $tmpFileCreateEvent) {
    /** @var TmpFile $tmpFile */
    $tmpFile = $tmpFileCreateEvent->getTmpFile();
    
    // ...
});
```

`TmpFileRemoveEvent::class` is fired before temp file removed:

```php
$eventDispatcher->addListener(TmpFileRemoveEvent::class, function (TmpFileRemoveEvent $tmpFileRemoveEvent) {
    /** @var TmpFile $tmpFile */
    $tmpFile = $tmpFileRemoveEvent->getTmpFile();
    
    // ...
});
```

`TmpFileManagerPurgeEvent::class` is fired before all temp files will are purge. Here may run unclosed resources handler:

```php
$eventDispatcher->addListener(TmpFileManagerPurgeEvent::class, function (TmpFileManagerPurgeEvent $tmpFileManagerPurgeEvent) {
    /** @var TmpFileManager $tmpFileManager */
    $tmpFileManager = $tmpFileManagerPurgeEvent->getTmpFileManager();
    
    // ...
});
```

After that you need to add event dispatcher in TmpFileManager to your event listeners run fire.

```php
/** @var \TmpFileManager\Config\ConfigInterface $config */
/** @var \TmpFileManager\Container\ContainerInterface $container */
/** @var \TmpFileManager\TmpFileHandler\TmpFileHandlerInterface $tmpFileHandler */

$tmpFileManager = new TmpFileManager($config, $container, $tmpFileHandler, $eventDispatcher);
```

## Advanced usage

You can get more control on manager if implement basic interfaces of inner services or extend exists. For example you will need strore temp files in cloud storage or change way of configuration.

```php
<?php

use TmpFile\TmpFile;
use TmpFileManager\Config\Config;
use TmpFileManager\Config\ConfigBuilder;
use TmpFileManager\Config\ConfigInterface;
use TmpFileManager\Container\Container;
use TmpFileManager\Container\ContainerInterface;
use TmpFileManager\TmpFileHandler\TmpFileHandler;
use TmpFileManager\TmpFileHandler\TmpFileHandlerInterface;
use TmpFileManager\TmpFileManager;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/** @var ConfigInterface $config */
$config = new Config(new ConfigBuilder());

/** @var ContainerInterface $container */
$container = new Container();

/** @var TmpFileHandlerInterface $tmpFileHandler */
$tmpFileHandler = new TmpFileHandler(new Filesystem());

/** @var EventDispatcherInterface $eventDispatcher */
$eventDispatcher = new EventDispatcher();
```

Next you must inject dependencies into to TmpFileManager:

```php
$tmpFileManager = new TmpFileManager($config, $container, $tmpFileHandler, $eventDispatcher);
```

After that manager's inner services are available to extend TmpFileManager use-cases in any part your application.

```php
/** @var ConfigInterface $config */
$config = $tmpFileManager->getConfig();

/** @var ContainerInterface $container */
$container = $tmpFileManager->getContainer();

/** @var TmpFileHandlerInterface $tmpFileHandler */
$tmpFileHandler = $tmpFileManager->getTmpFileHandler();

/** @var EventDispatcherInterface $eventDispatcher */
$eventDispatcher = $tmpFileManager->getEventDispatcher();
```
