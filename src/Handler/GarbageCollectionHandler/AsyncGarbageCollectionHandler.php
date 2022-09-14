<?php

declare(strict_types=1);

namespace TmpFileManager\Handler\GarbageCollectionHandler;

use Symfony\Component\Process\ExecutableFinder;
use Symfony\Component\Process\Process;
use TmpFileManager\Config\ConfigInterface;

/**
 * @codeCoverageIgnore
 */
final class AsyncGarbageCollectionHandler implements GarbageCollectionHandlerInterface
{
    private string $command;

    public function __construct(?ExecutableFinder $executableFinder = null, array $extraDirs = [])
    {
        $executableFinder ??= new ExecutableFinder();

        if (null === $command = $executableFinder->find('find', '/usr/bin/find', $extraDirs)) {
            throw new \RuntimeException("Util \"find\" isn't supporting.");
        }

        $this->command = $command;
    }

    public function handle(ConfigInterface $config): void
    {
        $tmpFileDir = $config->getTmpFileDirectory();
        $tmpFilePrefix = $config->getTmpFilePrefix();
        $gcProbability = $config->getGarbageCollectionProbability();
        $gcDivisor = $config->getGarbageCollectionDivisor();
        $gcLifetime = $config->getGarbageCollectionLifetime();

        if (0 === $gcProbability || mt_rand(1, $gcDivisor) > $gcProbability) {
            return;
        }

        $process = new Process([
            $this->command, $tmpFileDir,
            '-name', $tmpFilePrefix.'*',
            '-type', 'f',
            '-amin', '+'.ceil($gcLifetime / 60),
            '-maxdepth', 1,
            '-delete',
        ]);

        $process->setOptions([
            'create_new_console' => true,
        ]);

        $process->start();
    }
}
