<?php

namespace TmpFileManager\GarbageCollectionHandler;

use TmpFileManager\ConfigInterface;
use Symfony\Component\Process\Process;

class GarbageCollectionHandler implements GarbageCollectionHandlerInterface
{
    /**
     * @var string $executable
     * @var string $dir
     * @var string $prefix
     * @var int $probability
     * @var int $divisor
     * @var int $lifetime
     */
    private
        $executable,
        $dir,
        $prefix,
        $probability,
        $divisor,
        $lifetime
    ;

    public function __construct(string $executable = 'find')
    {
        $this->executable = $executable;
    }

    public function __invoke(ConfigInterface $config): void
    {
        $this->dir = $config->getTmpFileDirectory();
        $this->prefix = $config->getTmpFilePrefix();
        $this->probability = $config->getGarbageCollectionProbability();
        $this->divisor = $config->getGarbageCollectionDivisor();
        $this->lifetime = $config->getGarbageCollectionLifetime();

        if (!$this->isChance()) {
            return;
        }

        $this->handle();
    }

    private function isChance(): bool
    {
        return $this->probability == rand($this->probability, $this->divisor);
    }

    private function handle(): void
    {
        $minutes = $this->convertSecondsToMinutes($this->lifetime);

        $process = new Process([
            $this->executable, $this->dir,
            '-name', ($this->prefix . '*'),
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