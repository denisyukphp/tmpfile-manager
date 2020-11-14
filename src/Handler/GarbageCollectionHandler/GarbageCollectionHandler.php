<?php

namespace TmpFileManager\Handler\GarbageCollectionHandler;

use TmpFileManager\Config\ConfigInterface;
use Symfony\Component\Process\Process;

class GarbageCollectionHandler implements GarbageCollectionHandlerInterface
{
    /**
     * @var string
     */
    private $executable;

    public function __construct(string $executable = 'find')
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

        if ($this->isChance($probability, $divisor)) {
            $this->runProcess($dir, $prefix, $lifetime);
        }
    }

    private function isChance(int $probability, int $divisor): bool
    {
        return $probability == rand($probability, $divisor);
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
        return ceil($seconds / 60);
    }
}
