<?php

namespace TmpFileManager\GarbageCollectionHandler;

use TmpFileManager\ConfigInterface;

interface GarbageCollectionHandlerInterface
{
    public function handle(ConfigInterface $config): void;
}