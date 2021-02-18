<?php

namespace TmpFileManager\Provider;

use TmpFileManager\TmpFile\TmpFileInterface;

interface ProviderInterface
{
    public function getTmpFile(string $filename): TmpFileInterface;
}
