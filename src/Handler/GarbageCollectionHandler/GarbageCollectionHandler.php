<?php

declare(strict_types=1);

namespace TmpFileManager\Handler\GarbageCollectionHandler;

use TmpFileManager\Handler\GarbageCollectionHandler\Processor\ProcessorInterface;

final class GarbageCollectionHandler implements GarbageCollectionHandlerInterface
{
    public function __construct(
        private int $probability,
        private int $divisor,
        private int $lifetime,
        private ProcessorInterface $processor,
    ) {
    }

    public function handle(string $tmpFileDir, string $tmpFilePrefix): void
    {
        if (mt_rand(1, $this->divisor) <= $this->probability) {
            $this->processor->process($tmpFileDir, $tmpFilePrefix, $this->lifetime);
        }
    }
}
