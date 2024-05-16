<?php

declare(strict_types=1);

namespace TmpFileManager\Handler\GarbageCollectionHandler\Processor;

use Symfony\Component\Filesystem\Filesystem as Fs;
use Symfony\Component\Finder\Finder;

final class SyncProcessor implements ProcessorInterface
{
    private Fs $fs;

    public function __construct(Fs $fs = null)
    {
        $this->fs = $fs ?? new Fs();
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
            $this->fs->remove($finder->getIterator());
        }
    }
}
