<?php

declare(strict_types=1);

namespace TmpFileManager\Handler\GarbageCollectionHandler;

use Symfony\Component\Process\Process;
use TmpFileManager\Config\ConfigInterface;

/**
 * @codeCoverageIgnore
 */
final class NohupGarbageCollectionHandler implements GarbageCollectionHandlerInterface
{
    private string $nohupBin;
    private string $findBin;

    public function __construct(string $nohupBin = '/usr/bin/nohup', string $findBin = '/usr/bin/find')
    {
        $this->nohupBin = $nohupBin;
        $this->findBin = $findBin;
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

        $process = new Process([
            $this->nohupBin,
            $this->findBin, $dir,
            '-name', $prefix.'*',
            '-type', 'f',
            '-amin', '+'.ceil($lifetime / 60),
            '-maxdepth', 1,
            '-delete',
        ]);

        $process->run();
    }
}
