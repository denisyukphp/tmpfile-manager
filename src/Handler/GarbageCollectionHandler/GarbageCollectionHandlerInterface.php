<?php

declare(strict_types=1);

namespace TmpFileManager\Handler\GarbageCollectionHandler;

interface GarbageCollectionHandlerInterface
{
    public function handle(string $tmpFileDir, string $tmpFilePrefix): void;
}
