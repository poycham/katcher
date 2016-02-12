<?php


namespace Katcher\ServiceLayers;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;
use Katcher\Data\KatcherDownload;
use Katcher\Data\KatcherUrl;
use League\Flysystem\Adapter\Local;

class KatcherService
{
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

        return array_merge($meta, compact('getDownloadLink'));
    }
}