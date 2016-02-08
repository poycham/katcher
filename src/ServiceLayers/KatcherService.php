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
    public function downloadFiles($data)
    {
        $katcherURL = new KatcherUrl($data['url']);
        $container = container();
        /** @var $filesystem \League\Flysystem\Filesystem */
        $filesystem = $container->get('filesystem');

        $dir = str_replace('.ts', '', $katcherURL->format());
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
            'missing_files' => [],
            'nonexistent_files' => []
        ];

        $filesystem->write("{$dir}/meta.json", json_encode($meta, JSON_PRETTY_PRINT));

        /* download files */
        /** @var $guzzle Client */
        /** @var $localAdapter Local */
        $guzzle = $container->get('guzzle');
        $localAdapter = $filesystem->getAdapter();

        for ($i = $data['first_part']; $i <= $data['last_part']; $i++) {
            $retries = 0;

            while ($retries != KatcherDownload::RETRY_LIMIT) {
                try {
                    $response =  $guzzle->request('GET', $katcherURL->fileURL($i), [
                        'verify' => false,
                        'timeout' => KatcherDownload::CONNECTION_TIMEOUT
                    ]);
                } catch (ClientException $e) {
                    /* log missing file if not found */
                    $meta['nonexistent_files'][] = (int) $i;

                    /* download next file */
                    continue 2;
                } catch (RequestException $e) {
                    echo 'There is no connection';
                    $retries++;
                    exit;
                }

                break;
            }

            /* save file */
            $filesystem->write(
                "{$filesDir}/{$katcherURL->fileName($i)}",
                $response->getBody()->getContents()
            );
        }

        /* update log */
        $filesystem->update("{$dir}/meta.json", json_encode($meta, JSON_PRETTY_PRINT));
    }
}