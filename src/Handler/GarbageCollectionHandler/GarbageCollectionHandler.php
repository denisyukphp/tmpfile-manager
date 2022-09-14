<?php

declare(strict_types=1);

namespace TmpFileManager\Handler\GarbageCollectionHandler;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use TmpFileManager\Config\ConfigInterface;

final class GarbageCollectionHandler implements GarbageCollectionHandlerInterface
{
    private Filesystem $fs;

    public function __construct(?Filesystem $fs = null)
    {
        $this->fs = $fs ?? new Filesystem();
    }

    public function handle(ConfigInterface $config): void
    {
        $tmpFileDir = $config->getTmpFileDirectory();
        $tmpFilePrefix = $config->getTmpFilePrefix();
        $gcProbability = $config->getGarbageCollectionProbability();
        $gcDivisor = $config->getGarbageCollectionDivisor();
        $gcLifetime = $config->getGarbageCollectionLifetime();

        if (0 === $gcProbability || mt_rand(1, $gcDivisor) > $gcProbability) {
            return;
        }

        $finder = (new Finder())
            ->in($tmpFileDir)
            ->name($tmpFilePrefix.'*')
            ->depth('== 0')
            ->date('< '.date('Y-m-d H:i:s', time() - $gcLifetime))
            ->files()
        ;

        if (!$finder->hasResults()) {
            return;
        }

        $this->fs->remove($finder->getIterator());
    }
}
