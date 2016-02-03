<?php


namespace Katcher\ServiceLayers;


use League\Uri\Schemes\Http;

class KatcherService
{
    public function downloadFiles($data)
    {
        /*$url = Http::createFromString($data['url']);*/
        $uri = Http::createFromString('https://d152nid216lr13.cloudfront.net/6a155ef8-6571-38a6-8c8c-d83080d2428e/media-uwmn73350_5.ts');
        var_dump($uri);
        exit;
    }
}