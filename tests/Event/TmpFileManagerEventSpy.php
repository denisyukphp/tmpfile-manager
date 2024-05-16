<?php

declare(strict_types=1);

namespace TmpFileManager\Tests\Event;

use TmpFileManager\Event\AbstractTmpFileManagerEvent;

final class TmpFileManagerEventSpy
{
    /**
     * @param array<string, int> $eventsCounter
     * @param array<string, int> $tmpFilesCount
     */
    public function __construct(
        private array $eventsCounter = [],
        private array $tmpFilesCount = [],
    ) {
    }

    public function __invoke(AbstractTmpFileManagerEvent $event): void
    {
        if (!isset($this->eventsCounter[$event::class])) {
            $this->eventsCounter[$event::class] = 0;
        }

        if (!isset($this->tmpFilesCount[$event::class])) {
            $this->tmpFilesCount[$event::class] = 0;
        }

        ++$this->eventsCounter[$event::class];
        $this->tmpFilesCount[$event::class] = $event->getContainer()->count();
    }

    public function getEventsCount(string $className = null): int
    {
        if (null === $className) {
            return array_sum($this->eventsCounter);
        }

        if (!isset($this->eventsCounter[$className])) {
            return 0;
        }

        return $this->eventsCounter[$className];
    }

    public function getTmpFilesCount(string $className = null): int
    {
        if (null === $className) {
            return array_sum($this->tmpFilesCount);
        }

        if (!isset($this->tmpFilesCount[$className])) {
            return 0;
        }

        return $this->tmpFilesCount[$className];
    }
}
