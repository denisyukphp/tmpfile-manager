<?php

namespace TmpFileManager\GarbageCollectionHandler;

use TmpFileManager\ConfigInterface;
use Symfony\Contracts\EventDispatcher\Event;

class GarbageCollectionEvent extends Event
{
    private $config;

    public function __construct(ConfigInterface $config)
    {
        $this->config = $config;
    }

    public function getConfig(): ConfigInterface
    {
        return $this->config;
    }
}