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
    public function getRelativePath($path)
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

        return $adapter->applyPathPrefix($this->getRelativePath($path));
    }

    /**
     * Get files
     *
     * @return array
     */
    public function getFiles()
    {
        $files = $this->filesystem->listContents($this->getRelativePath('files'));

        /* sort files by extension number */
        $getNum = function($fileName) {
            preg_match('/([\d]+)\.ts$/', $fileName, $matches);

            return (int) $matches[1];
        };

        $sortByExtensionNumber = function($a, $b) use ($getNum) {
            $numA = $getNum($a['basename']);
            $numB = $getNum($b['basename']);

            if ($numA == $numB) {
                return 0;
            }
            return ($numA < $numB) ? -1 : 1;
        };

        usort($files, $sortByExtensionNumber);

        return $files;
    }

    /**
     * Read file
     *
     * @param string $file
     * @return bool|false|string
     */
    public function read($file)
    {
        return $this->filesystem->read($this->getRelativePath($file));
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
                $this->getRelativePath('meta.json')
            ),
            true
        );
    }

    /**
     * Create initial download storage
     *
     * @param string $folder
     * @param Filesystem $filesystem
     * @return static
     */
    public static function create($folder, Filesystem $filesystem)
    {
        /* create directory */
        $filesystem->createDir($folder);

        $downloadStorage = new static($folder, $filesystem);

        /* create files directory */
        $filesystem->createDir(
            $downloadStorage->getRelativePath('files')
        );

        return $downloadStorage;
    }
}