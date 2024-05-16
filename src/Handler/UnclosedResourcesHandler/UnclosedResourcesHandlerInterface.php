<?php

declare(strict_types=1);

namespace TmpFileManager\Handler\UnclosedResourcesHandler;

use TmpFile\TmpFileInterface;

interface UnclosedResourcesHandlerInterface
{
    /**
     * @param TmpFileInterface[] $tmpFiles
     */
    public function handle(array $tmpFiles): void;
}
