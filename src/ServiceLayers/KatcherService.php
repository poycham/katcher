<?php


namespace Katcher\ServiceLayers;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;
use Katcher\Components\DownloadMetaLog;
use Katcher\Components\DownloadStorage;
use Katcher\Data\KatcherDownload;
use Katcher\Data\KatcherUrl;
use pastuhov\Command\Command;

class KatcherService
{
    /**
     * @var \League\Container\Container
     */
    protected $container;

    /**
     * Create KatcherService
     */
    public function __construct()
    {
        $this->container = container();
    }

    /**
     * Download files
     *
     * @param $data
     * @return string
     */
    public function downloadFiles(array $data)
    {
        $katcherURL = new KatcherUrl($data['url']);
        $container = container();
        /** @var $filesystem \League\Flysystem\Filesystem */
        $filesystem = $container->get('filesystem');

        $dir = $katcherURL->folder();
        /* delete duplicate directory */
        if ($filesystem->has($dir)) {
            $filesystem->deleteDir($dir);
        }

        /* create directories */
        $filesDir = "{$dir}/files";

        $filesystem->createDir($dir);
        $filesystem->createDir($filesDir);

        /* create meta.json */
        $meta = [
            'status' => 'downloading',
            'url' => $katcherURL->fileURL('%i'),
            'missingFiles' => [],
            'nonexistentFiles' => [],
            'downloadRetries' => 0
        ];

        $filesystem->write("{$dir}/meta.json", json_encode($meta, JSON_PRETTY_PRINT));

        /* download files */
        /** @var $guzzle Client */
        $guzzle = $container->get('guzzle');

        for ($i = $data['first_part']; $i <= $data['last_part']; $i++) {
            $retries = 0;

            while ($retries != KatcherDownload::RETRY_LIMIT) {
                try {
                    $response =  $guzzle->request('GET', $katcherURL->fileURL($i), [
                        'verify' => false,
                        'timeout' => KatcherDownload::CONNECTION_TIMEOUT
                    ]);
                } catch (ClientException $e) {
                    /* log nonexistent file if not found */
                    $meta['nonexistentFiles'][] = (int) $i;

                    /* download next file */
                    continue 2;
                } catch (RequestException $e) {
                    $retries++;

                    /* log retries */
                    $meta['downloadRetries']++;

                    /* log missing file */
                    if ($retries == KatcherDownload::RETRY_LIMIT) {
                        $meta['missingFiles'][] = (int) $i;

                        /* download next file */
                        continue;
                    }

                    /* wait before retrying */
                    sleep(KatcherDownload::RETRY_WAIT_SECS);
                    continue;
                }

                /* save file */
                $filesystem->write(
                    "{$filesDir}/{$katcherURL->fileName($i)}",
                    $response->getBody()->getContents()
                );

                /* download next file */
                break;
            }
        }

        /* update log */
        $meta['status'] = 'downloaded';

        $filesystem->update("{$dir}/meta.json", json_encode($meta, JSON_PRETTY_PRINT));

        return $dir;
    }

    /**
     * View data for combiner page
     *
     * @param $folder
     * @return array
     */
    public function combinerViewData($folder)
    {
        /* conditional view data */
        $condViewData = [];

        /** @var $filesystem \League\Flysystem\Filesystem */
        $filesystem = container()->get('filesystem');

        $meta = json_decode(
            $filesystem->read("{$folder}/meta.json"),
            true
        );

        /* set getDownloadLink function */
        $katcherURL = new KatcherUrl($meta['url']);

        $getDownloadLink = function($filePart) use ($katcherURL) {
            return '<a href="'. $katcherURL->fileURL($filePart) .'">'. $filePart .'</a>';
        };

        /* set conditional view data */
        $hasMissingFiles = (count($meta['missingFiles']) > 0);
        $hasNonexistentFiles = (count($meta['nonexistentFiles']) > 0);
        $isAllDownloaded = (! $hasNonexistentFiles && ! $hasMissingFiles);

        if (! $isAllDownloaded) {
            if ($hasMissingFiles) {
                $condViewData['missingFiles'] = $meta['missingFiles'];
            }

            if ($hasNonexistentFiles) {
                $condViewData['nonexistentFiles'] = $meta['nonexistentFiles'];
            }
        }

        /* set view data */
        $viewData = array_merge(compact(
            'getDownloadLink',
            'hasMissingFiles',
            'hasNonexistentFiles',
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
        return $this->container->get('filesystem');
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