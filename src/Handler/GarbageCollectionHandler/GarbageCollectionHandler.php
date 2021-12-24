<?php

declare(strict_types=1);

namespace TmpFileManager\Handler\GarbageCollectionHandler;

use TmpFileManager\Config\ConfigInterface;
use Symfony\Component\Process\Process;

final class GarbageCollectionHandler implements GarbageCollectionHandlerInterface
{
    public function __construct(
        private string $executable = 'find',
    ) {
    }

    public function handle(ConfigInterface $config): void
    {
        $dir = $config->getTmpFileDirectory();
        $prefix = $config->getTmpFilePrefix();
        $probability = $config->getGarbageCollectionProbability();
        $divisor = $config->getGarbageCollectionDivisor();
        $lifetime = $config->getGarbageCollectionLifetime();

        if ($this->isChance($probability, $divisor)) {
            $this->runProcess($dir, $prefix, $lifetime);
        }
    }

    private function isChance(int $probability, int $divisor): bool
    {
        return $probability === mt_rand($probability, $divisor);
    }

    private function runProcess(string $dir, string $prefix, int $lifetime): void
    {
        $minutes = $this->convertSecondsToMinutes($lifetime);

        $process = new Process([
            $this->executable, $dir,
            '-name', ($prefix . '*'),
            '-type', 'f',
            '-amin', ('+' . $minutes),
            '-maxdepth', 1,
            '-delete',
        ]);

        $process->run();
    }

    private function convertSecondsToMinutes(int $seconds): int
    {
        return (int) ceil($seconds / 60);
    }
}
