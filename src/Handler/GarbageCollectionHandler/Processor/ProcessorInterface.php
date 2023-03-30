<?php

declare(strict_types=1);

namespace TmpFileManager\Handler\GarbageCollectionHandler\Processor;

interface ProcessorInterface
{
    public function process(string $tmpFileDirectory, string $tmpFilePrefix, int $tmpFileLifetimeInSeconds): void;
}
