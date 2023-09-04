<?php

declare(strict_types=1);

namespace TmpFileManager\Handler\GarbageCollectionHandler\Processor;

interface ProcessorInterface
{
    public function process(string $tmpFileDir, string $tmpFilePrefix, int $lifetime): void;
}
