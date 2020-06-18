# Garbage collection

Probability coupled with divisor defines the probability that the garbage collection process is started. The probability is calculated by using probability/divisor, e.g. 1/100 means there is a 1% chance that the garbage collection process start. Lifetime is seconds after which temp files will be seen as garbage and potentially cleaned up. 


```php
<?php

use Bulletproof\TmpFileManager\TmpFileManager;
use Bulletproof\TmpFileManager\ConfigBuilder;
use Bulletproof\TmpFileManager\GarbageCollectionHandler\GarbageCollectionHandler;

$config = (new ConfigBuilder())
    ->setGarbageCollectionProbability(1)
    ->setGarbageCollectionDivisor(100)
    ->setGarbageCollectionLifetime(3600)
    ->setGarbageCollectionHandler(new GarbageCollectionHandler())
    ->build()
;
```

Garbage collector process will start before installing the instance of TmpFileManager:

```
$tmpFileManager = new TmpFileManager($config);
```

Also you can start garbage collection process without installing the instance of TmpFileManger:

```php
$handler = $config->getGarbageCollectionHandler();

$handler($config);
```