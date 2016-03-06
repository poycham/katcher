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
     * Convert .ts files to .mp4
     *
     * @param string $folder
     */
    public function convertTsToMp4($folder)
    {
        $downloadStorage = new DownloadStorage($folder, $this->getFileSystem());
        $metaLog = DownloadMetaLog::read($downloadStorage);

        /* combine ts files */
        $combinedTsPath = $downloadStorage->getPath("{$folder}.ts");

        $this->combineTsFiles($combinedTsPath, $downloadStorage, $metaLog);

        /* convert combined ts file to mp4 */
        try {
            $convertedMp4Path = preg_replace('/\.ts$/', '.mp4', $combinedTsPath);

            Command::exec(
                'ffmpeg -loglevel quiet -y -i {combinedTsPath} -bsf:a aac_adtstoasc -acodec copy -vcodec copy {convertedMp4Path}',
                [
                    'combinedTsPath' => $combinedTsPath,
                    'convertedMp4Path' => $convertedMp4Path
                ]
            );

            $metaLog->set('status', 'converted');
        } catch (\Exception $e) {
            $metaLog->set('status', 'failed_conversion');
        }

        $metaLog->save()->close();
    }

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

    /**
     * Combine files
     *
     * @param string $combinedTsPath
     * @param DownloadStorage $downloadStorage
     * @param DownloadMetaLog $metaLog
     */
    private function combineTsFiles(
        $combinedTsPath,
        DownloadStorage $downloadStorage,
        DownloadMetaLog $metaLog
    ) {
        /* create all file */
        $combinedTsStream = fopen($combinedTsPath, 'w');

        /* write to all file stream */
        foreach ($downloadStorage->getFiles() as $data) {
            fwrite($combinedTsStream, $downloadStorage->readFilePart($data['basename']));
        }

        fclose($combinedTsStream);

        /* log status change */
        $metaLog->set('status', 'converting')->save();
    }
}