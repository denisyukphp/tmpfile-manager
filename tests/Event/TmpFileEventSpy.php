<?php

declare(strict_types=1);

namespace TmpFileManager\Tests\Event;

use TmpFile\TmpFileInterface;
use TmpFileManager\Event\AbstractTmpFileEvent;

final class TmpFileEventSpy
{
    /**
     * @param array<string, int>              $eventsCounter
     * @param array<string, TmpFileInterface> $tmpFiles
     */
    public function __construct(
        private array $eventsCounter = [],
        private array $tmpFiles = [],
    ) {
    }

    public function __invoke(AbstractTmpFileEvent $event): void
    {
        if (!isset($this->eventsCounter[$event::class])) {
            $this->eventsCounter[$event::class] = 0;
        }

        ++$this->eventsCounter[$event::class];
        $this->tmpFiles[$event->getTmpFile()->getFilename()] = $event->getTmpFile();
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

    /**
     * @return TmpFileInterface[]
     */
    public function getTmpFiles(): array
    {
        return array_values($this->tmpFiles);
    }
}
