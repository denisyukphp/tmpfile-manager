<?php

declare(strict_types=1);

namespace TmpFileManager\Handler\GarbageCollectionHandler\Processor;

use Symfony\Component\Process\ExecutableFinder;
use Symfony\Component\Process\Process;

final class AsyncProcessor implements ProcessorInterface
{
    /**
     * @param string[] $extraDirs
     */
    public function __construct(
        private bool $isParallel = true,
        private array $extraDirs = [],
    ) {
    }

    public function process(string $tmpFileDir, string $tmpFilePrefix, int $lifetime): void
    {
        $executable = (new ExecutableFinder())->find('find', '/usr/bin/find', $this->extraDirs);

        if (null === $executable) {
            throw new \RuntimeException('Async process can\'t be run because utility "find" not supported.'); // @codeCoverageIgnore
        }

        $process = new Process([
            $executable,
            $tmpFileDir,
            '-name', $tmpFilePrefix.'*',
            '-type', 'f',
            '-amin', '+'.ceil($lifetime / 60),
            '-maxdepth', 1,
            '-delete',
        ]);

        $process->start();

        if (!$this->isParallel) {
            $process->wait();
        }
    }
}
