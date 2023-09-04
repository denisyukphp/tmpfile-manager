<?php

declare(strict_types=1);

namespace TmpFileManager\Handler\GarbageCollectionHandler\Processor;

use Symfony\Component\Process\ExecutableFinder;
use Symfony\Component\Process\Process;

final class AsyncProcessor implements ProcessorInterface
{
    /**
     * @param string[] $extraExecutableCommandDirs
     */
    public function __construct(
        private bool $isParallelProcess = true,
        private array $extraExecutableCommandDirs = [],
    ) {
    }

    public function process(string $tmpFileDir, string $tmpFilePrefix, int $lifetime): void
    {
        $executableCommand = (new ExecutableFinder())
            ->find('find', '/usr/bin/find', $this->extraExecutableCommandDirs)
        ;

        if (null === $executableCommand) {
            throw new \RuntimeException('Async process can\'t be run because utility "find" not supported.');
        }

        $process = new Process([
            $executableCommand,
            $tmpFileDir,
            '-name', $tmpFilePrefix.'*',
            '-type', 'f',
            '-amin', '+'.ceil($lifetime / 60),
            '-maxdepth', 1,
            '-delete',
        ]);

        $process->setOptions([
            'create_new_console' => $this->isParallelProcess,
        ]);

        $process->start();
    }
}
