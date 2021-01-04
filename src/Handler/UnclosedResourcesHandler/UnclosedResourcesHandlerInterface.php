<?php

namespace TmpFileManager\Handler\UnclosedResourcesHandler;

use TmpFileManager\Container\ContainerInterface;

interface UnclosedResourcesHandlerInterface
{
    public function handle(ContainerInterface $container): void;
}
