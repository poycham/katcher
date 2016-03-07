<?php


namespace Katcher\ServiceLayers;


use Katcher\Components\DownloadMetaLog;
use Katcher\Components\DownloadStorage;
use Katcher\Data\KatcherUrl;
use pastuhov\Command\Command;

class ConvertService extends AbstractService
{
    /**
     * Get show view data
     *
     * @param $folder
     * @return array
     */
    public function getShowViewData($folder)
    {
        $condViewData = [];
        $downloadStorage = new DownloadStorage($folder, $this->app->get('filesystem'));
        $metaLog = DownloadMetaLog::read($downloadStorage);
        $hasMissingFiles = ($metaLog->count('missingFiles') > 0);
        $hasNonexistentFiles = ($metaLog->count('nonexistentFiles') > 0);
        $isAllDownloaded = (! $hasNonexistentFiles && ! $hasMissingFiles);

        if (! $isAllDownloaded) {
            if ($hasMissingFiles) {
                $condViewData['missingFiles'] = $metaLog->get('missingFiles');
                $condViewData['folder'] = $folder;
            }

            if ($hasNonexistentFiles) {
                $condViewData['nonexistentFiles'] = $metaLog->get('nonexistentFiles');
            }

            $condViewData['hasNonexistentFiles'] = $hasNonexistentFiles;
            $condViewData['katcherURL'] = KatcherUrl::createFromUrl($metaLog->get('url'));
        }

        $metaLog->close();

        /* set view data */
        $viewData = array_merge(compact(
            'isAllDownloaded',
            'hasMissingFiles'
        ), $condViewData);

        return $viewData;
    }

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