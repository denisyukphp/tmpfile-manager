<?php

namespace Bulletproof\TmpFileManager\GarbageCollectionHandler;

use Bulletproof\TmpFileManager\ConfigInterface;
use Symfony\Component\Process\Process;

class DefaultGarbageCollectionHandler implements GarbageCollectionHandlerInterface
{
    private
        $executable,
        $dir,
        $prefix,
        $probability,
        $divisor,
        $lifetime,
        $delay
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
        $this->delay = $config->getGarbageCollectionDelay();

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
        sleep($this->delay);

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