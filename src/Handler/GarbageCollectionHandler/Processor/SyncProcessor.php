<?php

declare(strict_types=1);

namespace TmpFileManager\Handler\GarbageCollectionHandler\Processor;

use Symfony\Component\Filesystem\Filesystem as SymfonyFilesystem;
use Symfony\Component\Finder\Finder;

final class SyncProcessor implements ProcessorInterface
{
    public function __construct(
        private SymfonyFilesystem $symfonyFilesystem,
    ) {
    }

    public function process(string $tmpFileDir, string $tmpFilePrefix, int $lifetime): void
    {
        $finder = (new Finder())
            ->in($tmpFileDir)
            ->name($tmpFilePrefix.'*')
            ->depth('== 0')
            ->date('< '.date('Y-m-d H:i:s', time() - $lifetime))
            ->files()
        ;

        if ($finder->hasResults()) {
            $this->symfonyFilesystem->remove($finder->getIterator());
        }
    }
}
