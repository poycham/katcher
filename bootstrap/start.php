<?php

use League\Flysystem\Adapter\Local;
use Symfony\Component\HttpFoundation\Request;

require __DIR__ . '/../vendor/autoload.php';

/* define dependencies */
$basePath = realpath(__DIR__) . '/../';

$container = new \League\Container\Container();

/* register path */
$container->singleton('path_generator', function() use ($basePath) {
    return new \Katcher\Components\PathGenerator($basePath);
});

/* register templates */
$templates = new \League\Plates\Engine("{$basePath}/resources/views", 'tpl.php');
$templates->loadExtension(
    new \League\Plates\Extension\Asset("{$basePath}/public", false)
);

$container->add('templates', $templates, true);

/* register local filesystem */
$container->add('filesystem', function() use ($basePath) {
    $adapter = new Local("{$basePath}/storage", LOCK_EX, Local::DISALLOW_LINKS, [
        'file' => [
            'public' => 0775,
            'private' => 0770,
        ],
        'dir' => [
            'public' => 0775,
            'private' => 0770,
        ]
    ]);

    return new \League\Flysystem\Filesystem($adapter, [
        'visibility' => \League\Flysystem\AdapterInterface::VISIBILITY_PRIVATE
    ]);
}, true);

$container->add('guzzle', 'GuzzleHttp\Client', true);

/* register request */
$container->singleton('request', function() {
    return Request::createFromGlobals();
});

/* register url */
$container->singleton('url_generator', function() use ($container) {
    /* get base url */
    /* @var Request $request */
    $request = $container->get('request');
    $baseURL = preg_replace(
        '/\/(index.php)?$/',
        '',
        $request->server->get('REDIRECT_URL')
    );

    return new \Katcher\Components\UrlGenerator($baseURL);
});

require __DIR__ . '/helpers.php';
require __DIR__ . '/routes.php';


