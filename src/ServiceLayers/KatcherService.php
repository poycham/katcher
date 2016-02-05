<?php


namespace Katcher\ServiceLayers;


use Katcher\Data\KatcherUrl;
use League\Uri\Schemes\Http;

class KatcherService
{
    public function downloadFiles($data)
    {
        $katcherURL = new KatcherUrl($data['url']);
        /** @var $filesystem \League\Flysystem\Filesystem */
        $filesystem = container()->get('filesystem');

        $dir = str_replace('.ts', '', $katcherURL->format());
        /* delete duplicate directory */
        if ($filesystem->has($dir)) {
            $filesystem->deleteDir($dir);
        }

        /* create directories */
        $filesystem->createDir($dir);
        $filesystem->createDir("{$dir}/files");

        /* create meta.json */
        $meta = [
            'status' => 'downloading',
            'missing_files' => []
        ];

        $filesystem->write("{$dir}/meta.json", json_encode($meta, JSON_PRETTY_PRINT));
    }
}