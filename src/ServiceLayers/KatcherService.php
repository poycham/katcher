<?php


namespace Katcher\ServiceLayers;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;
use Katcher\Components\DownloadMetaLog;
use Katcher\Components\DownloadStorage;
use Katcher\Data\Input;
use Katcher\Data\KatcherDownload;
use Katcher\Data\KatcherUrl;
use Katcher\Exceptions\ValidatorException;
use pastuhov\Command\Command;

class KatcherService extends AbstractService
{




    /**
     * Get download file path
     *
     * @param $folder
     * @return string
     */
    public function getDownloadFilePath($folder)
    {
        $downloadStorage = new DownloadStorage($folder, $this->getFileSystem());

        return $downloadStorage->getPath("{$folder}.mp4");
    }

    /**
     * Get download file contents
     *
     * @param $fileName
     * @return bool|false|string
     */
    public function getMp4FileContent($fileName)
    {
        $downloadStorage = new DownloadStorage(
            basename($fileName, '.mp4'),
            $this->getFileSystem()
        );

        return $downloadStorage->read($fileName);
    }

    /**
     * Get file system
     *
     * @return \League\Flysystem\Filesystem
     */
    private function getFileSystem()
    {
        return $this->app->get('filesystem');
    }
}