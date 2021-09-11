<?php

namespace TmpFileManager;

use TmpFile\TmpFileInterface;
use TmpFileManager\Config\Config;
use TmpFileManager\Config\ConfigInterface;
use TmpFileManager\Container\Container;
use TmpFileManager\Container\ContainerInterface;
use TmpFileManager\Filesystem\Filesystem;
use TmpFileManager\Filesystem\FilesystemInterface;
use TmpFileManager\Event\TmpFileManagerStartEvent;
use TmpFileManager\Event\TmpFileCreateEvent;
use TmpFileManager\Event\TmpFileRemoveEvent;
use TmpFileManager\Event\TmpFileManagerPurgeEvent;
use TmpFileManager\Listener\DeferredPurgeListener;
use TmpFileManager\Listener\GarbageCollectionListener;
use TmpFileManager\Listener\UnclosedResourcesListener;
use TmpFileManager\Provider\ReflectionProvider;
use TmpFileManager\Provider\ProviderInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;

final class TmpFileManager implements TmpFileManagerInterface
{
    /**
     * @var ConfigInterface
     */
    private $config;
    /**
     * @var ContainerInterface
     */
    private $container;
    /**
     * @var FilesystemInterface
     */
    private $filesystem;
    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;
    /**
     * @var ProviderInterface
     */
    private $provider;

    public function __construct(
        ?ConfigInterface $config = null,
        ?ContainerInterface $container = null,
        ?FilesystemInterface $filesystem = null,
        ?EventDispatcherInterface $eventDispatcher = null,
        ?ProviderInterface $provider = null
    ) {
        $this->config = $config ?? Config::default();
        $this->container = $container ?? new Container();
        $this->filesystem = $filesystem ?? Filesystem::create();
        $this->eventDispatcher = $eventDispatcher ?? new EventDispatcher();
        $this->provider = $provider ?? new ReflectionProvider();

        $this->addDefaultListener();

        $this->getEventDispatcher()->dispatch(new TmpFileManagerStartEvent($this));
    }

    private function addDefaultListener(): void
    {
        $this->getEventDispatcher()->addListener(TmpFileManagerStartEvent::class, new GarbageCollectionListener());
        $this->getEventDispatcher()->addListener(TmpFileManagerStartEvent::class, new DeferredPurgeListener());
        $this->getEventDispatcher()->addListener(TmpFileManagerPurgeEvent::class, new UnclosedResourcesListener());
    }

    public function getConfig(): ConfigInterface
    {
        return $this->config;
    }

    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }

    public function getFilesystem(): FilesystemInterface
    {
        return $this->filesystem;
    }

    public function getEventDispatcher(): EventDispatcherInterface
    {
        return $this->eventDispatcher;
    }

    public function getProvider(): ProviderInterface
    {
        return $this->provider;
    }

    public function createTmpFile(): TmpFileInterface
    {
        $dir = $this->getConfig()->getTmpFileDirectory();
        $prefix = $this->getConfig()->getTmpFilePrefix();

        $filename = $this->getFilesystem()->getTmpFileName($dir, $prefix);
        $tmpFile = $this->getProvider()->getTmpFile($filename);

        $this->getContainer()->addTmpFile($tmpFile);

        $this->getEventDispatcher()->dispatch(new TmpFileCreateEvent($tmpFile));

        return $tmpFile;
    }

    public function createTmpFileContext(callable $callback): void
    {
        $tmpFile = $this->createTmpFile();

        try {
            $callback($tmpFile);
        } finally {
            $this->removeTmpFile($tmpFile);
        }
    }

    public function removeTmpFile(TmpFileInterface $tmpFile): void
    {
        $this->getEventDispatcher()->dispatch(new TmpFileRemoveEvent($tmpFile));

        if ($this->getContainer()->hasTmpFile($tmpFile)) {
            $this->getContainer()->removeTmpFile($tmpFile);
        }

        if ($this->getFilesystem()->existsTmpFile($tmpFile)) {
            $this->getFilesystem()->removeTmpFile($tmpFile);
        }
    }

    public function purge(): void
    {
        $this->getEventDispatcher()->dispatch(new TmpFileManagerPurgeEvent($this));

        $tmpFiles = $this->getContainer()->getTmpFiles();

        foreach ($tmpFiles as $tmpFile) {
            $this->removeTmpFile($tmpFile);
        }
    }
}
