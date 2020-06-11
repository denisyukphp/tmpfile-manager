<?php

namespace TmpFileManager\DeferredPurgeHandler;

use TmpFileManager\TmpFileManagerInterface;
use TmpFileManager\ConfigInterface;
use Symfony\Contracts\EventDispatcher\Event;

class DeferredPurgeEvent extends Event
{
    private $tmpFileManager;

    private $config;

    public function __construct(TmpFileManagerInterface $tmpFileManager, ConfigInterface $config)
    {
        $this->tmpFileManager = $tmpFileManager;
        $this->config = $config;
    }

    public function getTmpFileManager(): TmpFileManagerInterface
    {
        return $this->tmpFileManager;
    }

    public function getConfig(): ConfigInterface
    {
        return $this->config;
    }
}