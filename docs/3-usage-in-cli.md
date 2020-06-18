# Usage in CLI

In CLI use `createTmpFileContext()` method to create and handle temp files. Temp files will immediately removed after finished callback.

```php
<?php

use Bulletproof\TmpFile\TmpFile;
use Bulletproof\TmpFileManager\TmpFileManager;

$tmpFileManager = new TmpFileManager();

$tmpFileManager->createTmpFileContext(function (TmpFile $tmpFile) {
    // ...
});
```