<?php

namespace Bulletproof\TmpFileManager;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;

interface TmpFileManagerServicesInterface
{
    public function getConfig(): ConfigInterface;

    public function getContainer(): ContainerInterface;

    public function getTmpFileHandler(): TmpFileHandlerInterface;

    public function getEventDispatcher(): EventDispatcherInterface;
}