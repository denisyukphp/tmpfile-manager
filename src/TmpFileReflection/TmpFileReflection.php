<?php

namespace TmpFileManager\TmpFileReflection;

use TmpFile\TmpFile;

class TmpFileReflection implements TmpFileReflectionInterface
{
    /**
     * @var \ReflectionClass
     */
    private $reflection;

    /**
     * @param string $className
     *
     * @throws \InvalidArgumentException
     * @throws \ReflectionException
     */
    public function __construct(string $className)
    {
        if (!is_a(TmpFile::class, $className, true)) {
            throw new \InvalidArgumentException(
                sprintf('Class name must be %s or inherit it', TmpFile::class)
            );
        }

        $this->reflection = new \ReflectionClass($className);
    }

    /**
     * @param string $realPath
     *
     * @return TmpFile
     */
    public function changeFilename(string $realPath): TmpFile
    {
        /** @var TmpFile $tmpFile */
        $tmpFile = $this->reflection->newInstanceWithoutConstructor();

        $filename = $this->reflection->getProperty('filename');

        $filename->setAccessible(true);

        $filename->setValue($tmpFile, $realPath);

        return $tmpFile;
    }
}
