<?php

namespace TmpFileManager\Config;

use TmpFileManager\Handler\DeferredPurgeHandler\DeferredPurgeHandlerInterface;
use TmpFileManager\Handler\DeferredPurgeHandler\DeferredPurgeHandler;
use TmpFileManager\Handler\UnclosedResourcesHandler\UnclosedResourcesHandlerInterface;
use TmpFileManager\Handler\UnclosedResourcesHandler\UnclosedResourcesHandler;
use TmpFileManager\Handler\GarbageCollectionHandler\GarbageCollectionHandlerInterface;
use TmpFileManager\Handler\GarbageCollectionHandler\GarbageCollectionHandler;

class ConfigBuilder
{
    /**
     * @var string
     */
    protected $tmpFileDirectory;
    /**
     * @var string
     */
    protected $tmpFilePrefix;
    /**
     * @var bool
     */
    protected $deferredPurge;
    /**
     * @var DeferredPurgeHandlerInterface
     */
    protected $deferredPurgeHandler;
    /**
     * @var bool
     */
    protected $unclosedResourcesCheck;
    /**
     * @var UnclosedResourcesHandlerInterface
     */
    protected $unclosedResourcesHandler;
    /**
     * @var int
     */
    protected $garbageCollectionProbability;
    /**
     * @var int
     */
    protected $garbageCollectionDivisor;
    /**
     * @var int
     */
    protected $garbageCollectionLifetime;
    /**
     * @var GarbageCollectionHandlerInterface
     */
    protected $garbageCollectionHandler;

    public function __construct()
    {
        $this->tmpFileDirectory = sys_get_temp_dir();
        $this->tmpFilePrefix = 'php';
        $this->deferredPurge = true;
        $this->deferredPurgeHandler = new DeferredPurgeHandler();
        $this->unclosedResourcesCheck = false;
        $this->unclosedResourcesHandler = new UnclosedResourcesHandler();
        $this->garbageCollectionProbability = 0;
        $this->garbageCollectionDivisor = 100;
        $this->garbageCollectionLifetime = 3600;
        $this->garbageCollectionHandler = new GarbageCollectionHandler();
    }

    public static function create(): self
    {
        return new self();
    }

    public function setTmpFileDirectory(string $tmpFileDirectory): self
    {
        $this->tmpFileDirectory = $tmpFileDirectory;

        return $this;
    }

    public function getTmpFileDirectory(): string
    {
        return $this->tmpFileDirectory;
    }

    public function setTmpFilePrefix(string $tmpFilePrefix): self
    {
        $this->tmpFilePrefix = $tmpFilePrefix;

        return $this;
    }

    public function getTmpFilePrefix(): string
    {
        return $this->tmpFilePrefix;
    }

    public function setDeferredPurge(bool $deferredPurge): self
    {
        $this->deferredPurge = $deferredPurge;

        return $this;
    }

    public function getDeferredPurge(): bool
    {
        return $this->deferredPurge;
    }

    public function setDeferredPurgeHandler(DeferredPurgeHandlerInterface $deferredPurgeHandler): self
    {
        $this->deferredPurgeHandler = $deferredPurgeHandler;

        return $this;
    }

    public function getDeferredPurgeHandler(): DeferredPurgeHandlerInterface
    {
        return $this->deferredPurgeHandler;
    }

    public function setUnclosedResourcesCheck(bool $unclosedResourcesCheck): self
    {
        $this->unclosedResourcesCheck = $unclosedResourcesCheck;

        return $this;
    }

    public function getUnclosedResourcesCheck(): bool
    {
        return $this->unclosedResourcesCheck;
    }

    public function setUnclosedResourcesHandler(UnclosedResourcesHandlerInterface $unclosedResourcesHandler): self
    {
        $this->unclosedResourcesHandler = $unclosedResourcesHandler;

        return $this;
    }

    public function getUnclosedResourcesHandler(): UnclosedResourcesHandlerInterface
    {
        return $this->unclosedResourcesHandler;
    }

    public function setGarbageCollectionProbability(int $garbageCollectionProbability): self
    {
        $this->garbageCollectionProbability = $garbageCollectionProbability;

        return $this;
    }

    public function getGarbageCollectionProbability(): int
    {
        return $this->garbageCollectionProbability;
    }

    public function setGarbageCollectionDivisor(int $garbageCollectionDivisor): self
    {
        $this->garbageCollectionDivisor = $garbageCollectionDivisor;

        return $this;
    }

    public function getGarbageCollectionDivisor(): int
    {
        return $this->garbageCollectionDivisor;
    }

    public function setGarbageCollectionLifetime(int $garbageCollectionLifetime): self
    {
        $this->garbageCollectionLifetime = $garbageCollectionLifetime;

        return $this;
    }

    public function getGarbageCollectionLifetime(): int
    {
        return $this->garbageCollectionLifetime;
    }

    public function setGarbageCollectionHandler(GarbageCollectionHandlerInterface $garbageCollectionHandler): self
    {
        $this->garbageCollectionHandler = $garbageCollectionHandler;

        return $this;
    }

    public function getGarbageCollectionHandler(): GarbageCollectionHandlerInterface
    {
        return $this->garbageCollectionHandler;
    }

    public function build(): Config
    {
        return new Config($this);
    }
}
