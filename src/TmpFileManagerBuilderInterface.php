<?php

declare(strict_types=1);

namespace TmpFileManager;

interface TmpFileManagerBuilderInterface
{
    public function build(): TmpFileManagerInterface;
}
