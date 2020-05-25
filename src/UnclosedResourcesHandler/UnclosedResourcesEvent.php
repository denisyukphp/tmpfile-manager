<?php

namespace TmpFileManager\UnclosedResourcesHandler;

use TmpFile\TmpFile;
use TmpFileManager\ConfigInterface;
use Symfony\Contracts\EventDispatcher\Event;

class UnclosedResourcesEvent extends Event
{
    private $config;

    /** @var TmpFile[] */
    private $tmpFiles;

    /**
     * @param ConfigInterface $config
     * @param TmpFile[] $tmpFiles
     */
    public function __construct(ConfigInterface $config, array $tmpFiles)
    {
        $this->config = $config;
        $this->tmpFiles = $tmpFiles;
    }

    public function getConfig(): ConfigInterface
    {
        return $this->config;
    }

    /**
     * @return TmpFile[]
     */
    public function getTmpFiles(): array
    {
        return $this->tmpFiles;
    }
}