<?php

namespace TmpFileManager\CloseOpenedResourcesHandler;

use TmpFile\TmpFile;

interface UnclosedResourcesHandlerInterface
{
    /**
     * @param TmpFile[] $tmpFiles
     */
    public function __invoke(array $tmpFiles): void;
}