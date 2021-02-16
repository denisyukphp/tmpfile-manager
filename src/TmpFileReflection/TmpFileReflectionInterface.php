<?php

namespace TmpFileManager\TmpFileReflection;

use TmpFile\TmpFile;

interface TmpFileReflectionInterface
{
    public function changeFilename(string $realPath): TmpFile;
}
