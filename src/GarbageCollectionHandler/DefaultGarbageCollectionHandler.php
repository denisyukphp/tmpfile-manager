<?php

namespace TmpFileManager\GarbageCollectionHandler;

use TmpFileManager\ConfigInterface;
use Symfony\Component\Process\Process;

class DefaultGarbageCollectionHandler implements GarbageCollectionHandlerInterface
{
    private $executable;

    public function __construct(string $executable = 'find')
    {
        $this->executable = $executable;
    }

    public function __invoke(ConfigInterface $config): void
    {
        $dir = $config->getTmpFileDirectory();
        $prefix = $config->getTmpFilePrefix();
        $probability = $config->getGarbageCollectionProbability();
        $divisor = $config->getGarbageCollectionDivisor();
        $lifetime = $config->getGarbageCollectionLifetime();

        if ($this->isChance($probability, $divisor)) {
            $this->process($dir, $prefix, $lifetime);
        }
    }

    private function isChance(int $probability, int $divisor): bool
    {
        return $probability == rand($probability, $divisor);
    }

    private function process(string $dir, string $prefix, int $lifetime): void
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
        return ceil($seconds / 60);
    }
}