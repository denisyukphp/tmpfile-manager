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
    private string $executable;

    public function __construct(string $executable = '/usr/bin/find')
    {
        $this->executable = $executable;
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
            'nohup '.$this->executable, $dir,
            '-name', $prefix.'*',
            '-type', 'f',
            '-amin', '+'.ceil($lifetime / 60),
            '-maxdepth', 1,
            '-delete',
        ]);

        $process->run();
    }
}
