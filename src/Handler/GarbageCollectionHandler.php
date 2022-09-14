<?php

declare(strict_types=1);

namespace TmpFileManager\Handler;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use TmpFileManager\Config\ConfigInterface;
use TmpFileManager\Handler\GarbageCollectionHandler\GarbageCollectionHandlerInterface;

/**
 * @codeCoverageIgnore
 */
final class GarbageCollectionHandler implements GarbageCollectionHandlerInterface
{
    private int $probability;
    private int $divisor;
    private int $lifetime;
    private Filesystem $fs;

    public function __construct(int $probability = 1, int $divisor = 100, int $lifetime = 1440, ?Filesystem $fs = null)
    {
        $this->probability = $probability;
        $this->divisor = $divisor;
        $this->lifetime = $lifetime;
        $this->fs = $fs ?? new Filesystem();
    }

    public function handle(ConfigInterface $config): void
    {
        if (0 === $this->probability || mt_rand(1, $this->divisor) > $this->probability) {
            return;
        }

        $finder = (new Finder())
            ->in($config->getTmpFileDirectory())
            ->name($config->getTmpFilePrefix().'*')
            ->depth('== 0')
            ->date('< '.date('Y-m-d H:i:s', time() - $this->lifetime))
            ->files()
        ;

        if (!$finder->hasResults()) {
            return;
        }

        $this->fs->remove($finder->getIterator());
    }
}
