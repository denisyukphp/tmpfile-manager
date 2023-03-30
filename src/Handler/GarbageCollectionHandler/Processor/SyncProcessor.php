<?php

declare(strict_types=1);

namespace TmpFileManager\Handler\GarbageCollectionHandler\Processor;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

final class SyncProcessor implements ProcessorInterface
{
    private Filesystem $filesystem;

    public function __construct(?Filesystem $filesystem = null)
    {
        $this->filesystem = $filesystem ?? new Filesystem();
    }

    public function process(string $tmpFileDirectory, string $tmpFilePrefix, int $tmpFileLifetimeInSeconds): void
    {
        $finder = (new Finder())
            ->in($tmpFileDirectory)
            ->name($tmpFilePrefix.'*')
            ->depth('== 0')
            ->date('< '.date('Y-m-d H:i:s', time() - $tmpFileLifetimeInSeconds))
            ->files()
        ;

        if ($finder->hasResults()) {
            $this->filesystem->remove($finder->getIterator());
        }
    }
}
