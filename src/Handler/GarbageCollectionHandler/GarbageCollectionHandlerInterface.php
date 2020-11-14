<?php

namespace TmpFileManager\Handler\GarbageCollectionHandler;

use TmpFileManager\Config\ConfigInterface;

interface GarbageCollectionHandlerInterface
{
    public function handle(ConfigInterface $config): void;
}
