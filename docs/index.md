# Documentation

- [Default configuration](#default-configuration)
- [Removing temp files](#removing-temp-files)
- [Check unclosed resources](#check-unclosed-resources)
- [Garbage collection](#garbage-collection)
- [Custom handlers](#custom-handlers)
- [Advanced usage](#advanced-usage)
- [Subscribe events](#subscribe-events)

## Default configuration

Configure TmpFileManager with config builder. By default temp files will purge automatically. Unclosed resources check and garbage collection are off. Below is the default configuration:

```php
<?php

use Bulletproof\TmpFileManager\TmpFileManager;
use Bulletproof\TmpFileManager\ConfigBuilder;

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

TmpFileManager can close open resources automatically before purge temp files. Configure `setUnclosedResourcesCheck()` to `true`.

```php
<?php

use Bulletproof\TmpFile\TmpFile;
use Bulletproof\TmpFileManager\TmpFileManager;
use Bulletproof\TmpFileManager\ConfigBuilder;

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

The probability is calculated by using probability/divisor, e.g. 1/100 means there is a 1% chance that the garbage collection process start. Lifetime is seconds after which temp files will be seen as garbage and potentially cleaned up. 

```php
<?php

use Bulletproof\TmpFileManager\TmpFileManager;
use Bulletproof\TmpFileManager\ConfigBuilder;

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

Define your handlers to get more control for manage temp files with config builder. Below is the default handlers:

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

Each handler implements yourself interface. DeferredPurgeHandlerInterface needed for implements temp files purge when PHP finished. You can make your handler implementing this interface:


```php
<?php

use Bulletproof\TmpFileManager\TmpFileManager;
use Bulletproof\TmpFileManager\DeferredPurgeHandler\DeferredPurgeHandlerInterface;

class DeferredPurgeHandler implements DeferredPurgeHandlerInterface
{
    public function __invoke(TmpFileManager $tmpFileManager): void
    {
        // ...
    }
}
```

For replace unclosed resources handler needed implement UnclosedResourcesHandlerInterface:

```php
<?php

use Bulletproof\TmpFile\TmpFile;
use Bulletproof\TmpFileManager\UnclosedResourcesHandler\UnclosedResourcesHandlerInterface;

class UnclosedResourcesHandler implements UnclosedResourcesHandlerInterface
{
    /**
     * @param TmpFile[] $tmpFiles
     */
    public function __invoke(array $tmpFiles): void
    {
        // ...
    }
}
```

Garbage collection process has yourself handler which implements GarbageCollectionHandlerInterface. Implement this interface to change garbage collection handler by default:

```php
<?php

use Bulletproof\TmpFileManager\ConfigInterface;
use Bulletproof\TmpFileManager\GarbageCollectionHandler\GarbageCollectionHandlerInterface;

class GarbageCollectionHandler implements GarbageCollectionHandlerInterface
{
    public function __invoke(ConfigInterface $config): void
    {
        // ...
    }
}
```

## Advanced usage

You can get more control on manager if implement basic interfaces of inner services or extend exists. For example you will need strore temp files in cloud storage or change way of configuration.

```php
<?php

use Bulletproof\TmpFile\TmpFile;
use Bulletproof\TmpFileManager\TmpFileManager;
use Bulletproof\TmpFileManager\Config;
use Bulletproof\TmpFileManager\ConfigBuilder;
use Bulletproof\TmpFileManager\ConfigInterface;
use Bulletproof\TmpFileManager\Container;
use Bulletproof\TmpFileManager\ContainerInterface;
use Bulletproof\TmpFileManager\TmpFileHandler;
use Bulletproof\TmpFileManager\TmpFileHandlerInterface;
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

After that manager's inner services are availbale to extend TmpFileManager use-cases in any part your application.

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

## Subscribe events

With EventDispatcher you can subsribe manager's events to inject your code in lifecycle of temp files.

```php
<?php

use Bulletproof\TmpFile\TmpFile;
use Bulletproof\TmpFileManager\TmpFileManager;
use Bulletproof\TmpFileManager\StartEvent;
use Bulletproof\TmpFileManager\CreateEvent;
use Bulletproof\TmpFileManager\RemoveEvent;
use Bulletproof\TmpFileManager\PurgeEvent;
use Symfony\Component\EventDispatcher\EventDispatcher;

$eventDispatcher = new EventDispatcher();
```

`StartEvent::class` is fired when TmpFileManager constructing. Here deferred purge handler and garbage collector are register. This event haven't access to temp files:

```php
$eventDispatcher->addListener(StartEvent::class, function (StartEvent $startEvent) {
    /** @var TmpFileManager $tmpFileManager */
    $tmpFileManager = $startEvent->getTmpFileManager();
    
    // ...
});
```

`CreateEvent::class` is fired after temp file created:

```php
$eventDispatcher->addListener(CreateEvent::class, function (CreateEvent $createEvent) {
    /** @var TmpFile $tmpFile */
    $tmpFile = $createEvent->getTmpFile();
    
    // ...
});
```

`RemoveEvent::class` is fired before temp file removed:

```php
$eventDispatcher->addListener(RemoveEvent::class, function (RemoveEvent $removeEvent) {
    /** @var TmpFile $tmpFile */
    $tmpFile = $removeEvent->getTmpFile();
    
    // ...
});
```

`PurgeEvent::class` is fired before all temp files will are purge. Here may run unclosed resources handler:

```php
$eventDispatcher->addListener(PurgeEvent::class, function (PurgeEvent $purgeEvent) {
    /** @var TmpFileManager $tmpFileManager */
    $tmpFileManager = $purgeEvent->getTmpFileManager();
    
    // ...
});
```