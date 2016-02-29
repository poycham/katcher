<?php


namespace Katcher\ServiceLayers;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;
use Katcher\App;
use Katcher\Components\DownloadMetaLog;
use Katcher\Components\DownloadStorage;
use Katcher\Data\KatcherDownload;
use Katcher\Data\KatcherUrl;
use pastuhov\Command\Command;

class KatcherService
{
    /**
     * @var App
     */
    protected $app;

    /**
     * Create KatcherService
     * @param App $app
     */
    public function __construct(App $app)
    {
        $this->app = $app;
    }

    /**
     * Download .ts files
     *
     * @param $data
     * @return string
     */
    public function downloadTs(array $data)
    {
        $katcherURL = new KatcherUrl($data['url']);
        $filesystem = $this->getFileSystem();
        $folder = $katcherURL->folder();

        /* delete duplicate directory */
        if ($filesystem->has($folder)) {
            $filesystem->deleteDir($folder);
        }

        /* create download storage */
        $downloadStorage = DownloadStorage::create($folder, $filesystem);

        /* initialize log */
        $metaLog = DownloadMetaLog::create([
            'status' => 'downloading',
            'currentFile' => 0,
            'url' => $katcherURL->fileURL('%i'),
            'parts' => [
                'first' => $data['first_part'],
                'last' => $data['last_part']
            ],
            'missingFiles' => [],
            'nonexistentFiles' => [],
            'downloadRetries' => 0
        ], $downloadStorage);

        /* download files */
        $guzzle = new Client();

        for ($i = $data['first_part']; $i <= $data['last_part']; $i++) {
            $metaLog->set('currentFile', $i)->save();

            $retries = 0;

            while ($retries != KatcherDownload::RETRY_LIMIT) {
                try {
                    $response =  $guzzle->request('GET', $katcherURL->fileURL($i), [
                        'verify' => false,
                        'timeout' => KatcherDownload::CONNECTION_TIMEOUT
                    ]);
                } catch (ClientException $e) {
                    $metaLog->push('nonexistentFiles', (int) $i)->save();

                    /* download next file */
                    continue 2;
                } catch (RequestException $e) {
                    $metaLog->increment('downloadRetries')->save();

                    $retries++;

                    /* handle reaching the retry limit */
                    if ($retries == KatcherDownload::RETRY_LIMIT) {
                        $metaLog->push('missingFiles', (int) $i)->save();

                        /* download next file */
                        continue;
                    }

                    /* wait before retrying */
                    sleep(KatcherDownload::RETRY_WAIT_SECS);
                    continue;
                }

                /* save file */
                $downloadStorage->writeFile(
                    $katcherURL->fileName($i),
                    $response->getBody()->getContents()
                );

                /* download next file */
                break;
            }
        }

        /* update log */
        $metaLog->set('status', 'downloaded')
            ->save()
            ->close();

        return $folder;
    }

    /**
     * View data for combiner page
     *
     * @param $folder
     * @return array
     */
    public function getConvertViewData($folder)
    {
        $condViewData = [];

        /* set conditional view data */
        $downloadStorage = new DownloadStorage($folder, $this->getFileSystem());
        $metaLog = DownloadMetaLog::read($downloadStorage);
        $hasMissingFiles = ($metaLog->count('missingFiles') > 0);
        $hasNonexistentFiles = ($metaLog->count('nonexistentFiles') > 0);
        $isAllDownloaded = (! $hasNonexistentFiles && ! $hasMissingFiles);

        if (! $isAllDownloaded) {
            if ($hasMissingFiles) {
                $condViewData['missingFiles'] = $metaLog->get('missingFiles');
            }

            if ($hasNonexistentFiles) {
                $condViewData['nonexistentFiles'] = $metaLog->get('nonexistentFiles');
            }

            $condViewData['hasMissingFiles'] = $hasMissingFiles;
            $condViewData['hasNonexistentFiles'] = $hasNonexistentFiles;
            $condViewData['katcherURL'] = new KatcherUrl($metaLog->get('url'));
        }

        /* set view data */
        $viewData = array_merge(compact(
            'isAllDownloaded'
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
        $combinedTsPath = $downloadStorage->path("{$folder}.ts");

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

        $metaLog->save();
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

        return $downloadStorage->path("{$folder}.mp4");
    }

    /**
     * Get download file contents
     *
     * @param $fileName
     * @return bool|false|string
     */
    public function getDownloadFileContent($fileName)
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