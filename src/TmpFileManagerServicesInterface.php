<?php

namespace TmpFileManager;

use TmpFileManager\Config\ConfigInterface;
use TmpFileManager\Container\ContainerInterface;
use TmpFileManager\TmpFileHandler\TmpFileHandlerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

interface TmpFileManagerServicesInterface
{
    public function getConfig(): ConfigInterface;

    public function getContainer(): ContainerInterface;

    public function getTmpFileHandler(): TmpFileHandlerInterface;

    public function getEventDispatcher(): EventDispatcherInterface;
}
