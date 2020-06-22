<?php

namespace TmpFileManager\UnclosedResourcesHandler;

use TmpFile\TmpFileInterface;

interface UnclosedResourcesHandlerInterface
{
    /**
     * @param TmpFileInterface[] $tmpFiles
     */
    public function __invoke(array $tmpFiles): void;
}