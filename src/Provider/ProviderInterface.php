<?php

namespace TmpFileManager\Provider;

use TmpFile\TmpFileInterface;

interface ProviderInterface
{
    public function getTmpFile(string $filename): TmpFileInterface;
}
