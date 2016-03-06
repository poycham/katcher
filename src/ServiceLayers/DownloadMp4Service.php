<?php


namespace Katcher\ServiceLayers;


use Katcher\Components\DownloadStorage;

class DownloadMp4Service extends AbstractService
{
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
            $this->app->get('filesystem')
        );

        return $downloadStorage->read($fileName);
    }
}