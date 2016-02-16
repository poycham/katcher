<?php


namespace Katcher\Components;


use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;

class DownloadStorage
{
    /**
     * @var string
     */
    protected $folder;

    /**
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * Create download storage
     *
     * @param string $folder
     * @param Filesystem $filesystem
     */
    public function __construct($folder, Filesystem $filesystem)
    {
        $this->folder = $folder;
        $this->filesystem = $filesystem;
    }

    /**
     * Get file system
     *
     * @return Filesystem
     */
    public function filesystem()
    {
        return $this->filesystem;
    }

    /**
     * Get relative path
     *
     * @param $path
     * @return string
     */
    public function relativePath($path)
    {
        return "{$this->folder}/{$path}";
    }

    /**
     * Get absolute path
     *
     * @param $path
     * @return string
     */
    public function path($path)
    {
        /** @var Local $adapter */
        $adapter = $this->filesystem->getAdapter();

        return $adapter->applyPathPrefix($this->relativePath($path));
    }

    /**
     * Get files
     *
     * @return array
     */
    public function getFiles()
    {
        return $this->filesystem->listContents($this->relativePath('files'));
    }

    /**
     * Read file
     *
     * @param string $file
     * @return bool|false|string
     */
    public function read($file)
    {
        return $this->filesystem->read($this->relativePath($file));
    }

    /**
     * Read file part
     *
     * @param $fileName
     * @return bool|false|string
     */
    public function readFilePart($fileName)
    {
        return $this->read("files/{$fileName}");
    }

    /**
     * Get meta
     *
     * @return array
     */
    public function meta()
    {
        return json_decode(
            $this->filesystem->read(
                $this->relativePath('meta.json')
            ),
            true
        );
    }
}