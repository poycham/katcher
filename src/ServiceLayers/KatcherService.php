<?php


namespace Katcher\ServiceLayers;


use GuzzleHttp\Client;
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
            'missing_files' => []
        ];

        $filesystem->write("{$dir}/meta.json", json_encode($meta, JSON_PRETTY_PRINT));

        /* download files */
        /** @var $guzzle Client */
        $guzzle = $container->get('guzzle');
        /** @var $localAdapter Local */
        $localAdapter = $filesystem->getAdapter();

        for ($i = $data['first_part']; $i <= $data['last_part']; $i++) {
            $fileName = $katcherURL->fileName($i);
            $filePath = $localAdapter->applyPathPrefix("{$filesDir}/{$fileName}");

            $fileContent =  $guzzle->request('GET', $katcherURL->fileURL($i), [
                'verify' => false,
                'sink' => $filePath,
                'timeout' => 3.14
            ]);
        }
    }
}