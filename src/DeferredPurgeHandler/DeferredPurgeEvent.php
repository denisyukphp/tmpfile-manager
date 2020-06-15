<?php

namespace Bulletproof\TmpFileManager\DeferredPurgeHandler;

use Bulletproof\TmpFileManager\TmpFileManagerInterface;
use Bulletproof\TmpFileManager\ConfigInterface;
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