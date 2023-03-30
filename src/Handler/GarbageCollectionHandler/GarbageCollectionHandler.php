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

    public function handle(string $tmpFileDirectory, string $tmpFilePrefix): void
    {
        if ($this->isChance($this->probability, $this->divisor)) {
            $this->processor->process($tmpFileDirectory, $tmpFilePrefix, $this->lifetime);
        }
    }

    private function isChance(int $probability, int $divisor): bool
    {
        return 0 !== $probability && mt_rand(1, $divisor) > $probability;
    }
}
