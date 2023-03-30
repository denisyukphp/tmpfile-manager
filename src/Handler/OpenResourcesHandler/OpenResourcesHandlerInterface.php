<?php

declare(strict_types=1);

namespace TmpFileManager\Handler\OpenResourcesHandler;

use TmpFile\TmpFileInterface;

interface OpenResourcesHandlerInterface
{
    /**
     * @param TmpFileInterface[] $tmpFiles
     */
    public function handle(array $tmpFiles): void;
}
