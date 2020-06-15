<?php

namespace Bulletproof\TmpFileManager\GarbageCollectionHandler;

use Bulletproof\TmpFileManager\ConfigInterface;

interface GarbageCollectionHandlerInterface
{
    public function __invoke(ConfigInterface $config): void;
}