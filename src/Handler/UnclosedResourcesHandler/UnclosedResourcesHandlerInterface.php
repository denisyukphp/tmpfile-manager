<?php

declare(strict_types=1);

namespace TmpFileManager\Handler\UnclosedResourcesHandler;

use TmpFileManager\Container\ContainerInterface;

interface UnclosedResourcesHandlerInterface
{
    public function handle(ContainerInterface $container): void;
}
