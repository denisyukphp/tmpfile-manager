<?php

namespace Bulletproof\TmpFileManager\UnclosedResourcesHandler;

use Bulletproof\TmpFile\TmpFileInterface;
use Bulletproof\TmpFileManager\ConfigInterface;
use Symfony\Contracts\EventDispatcher\Event;

class UnclosedResourcesEvent extends Event
{
    private $config;

    /**
     * @var TmpFileInterface[]
     */
    private $tmpFiles;

    /**
     * @param ConfigInterface $config
     * @param TmpFileInterface[] $tmpFiles
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
     * @return TmpFileInterface[]
     */
    public function getTmpFiles(): array
    {
        return $this->tmpFiles;
    }
}