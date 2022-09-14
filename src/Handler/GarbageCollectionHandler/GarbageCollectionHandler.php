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
        $dir = $config->getTmpFileDirectory();
        $prefix = $config->getTmpFilePrefix();
        $probability = $config->getGarbageCollectionProbability();
        $divisor = $config->getGarbageCollectionDivisor();
        $lifetime = $config->getGarbageCollectionLifetime();

        if (0 === $probability || mt_rand(1, $divisor) > $probability) {
            return;
        }

        $finder = (new Finder())
            ->in($dir)
            ->name($prefix.'*')
            ->depth('== 0')
            ->date('< '.date('Y-m-d H:i:s', time() - $lifetime))
            ->files()
        ;

        if (!$finder->hasResults()) {
            return;
        }

        $this->fs->remove($finder->getIterator());
    }
}
