<?php

namespace Bulletproof\TmpFileManager\UnclosedResourcesHandler;

use Bulletproof\TmpFile\TmpFileInterface;

interface UnclosedResourcesHandlerInterface
{
    /**
     * @param TmpFileInterface[] $tmpFiles
     */
    public function __invoke(array $tmpFiles): void;
}