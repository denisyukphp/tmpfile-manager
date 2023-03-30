<?php

declare(strict_types=1);

namespace TmpFileManager\Handler\GarbageCollectionHandler\Processor;

use Symfony\Component\Process\ExecutableFinder;
use Symfony\Component\Process\Process;

final class AsyncProcessor implements ProcessorInterface
{
    private string $command;

    /**
     * @param string[] $extraCommandDirectories
     */
    public function __construct(array $extraCommandDirectories = [])
    {
        $command = (new ExecutableFinder())->find('find', '/usr/bin/find', $extraCommandDirectories);

        if (null === $command) {
            throw new \RuntimeException('Util "find" isn\'t supporting.');
        }

        $this->command = $command;
    }

    public function process(string $tmpFileDirectory, string $tmpFilePrefix, int $tmpFileLifetimeInSeconds): void
    {
        $process = new Process([
            $this->command,
            $tmpFileDirectory,
            '-name', $tmpFilePrefix.'*',
            '-type', 'f',
            '-amin', '+'.ceil($tmpFileLifetimeInSeconds / 60),
            '-maxdepth', 1,
            '-delete',
        ]);

        $process->setOptions([
            'create_new_console' => true,
        ]);

        $process->start();
    }
}
