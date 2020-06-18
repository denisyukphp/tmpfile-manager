# Check unclosed resources

TmpFileManager can close open resources automatically before purge temp files. Configure `setUnclosedResourcesCheck()` to `true`.

```php
<?php

use Bulletproof\TmpFile\TmpFile;
use Bulletproof\TmpFileManager\TmpFileManager;
use Bulletproof\TmpFileManager\ConfigBuilder;
use Bulletproof\TmpFileManager\UnclosedResourcesHandler\UnclosedResourcesHandler;

$config = (new ConfigBuilder())
    ->setUnclosedResourcesCheck(true)
    ->setUnclosedResourcesHandler(new UnclosedResourcesHandler())
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