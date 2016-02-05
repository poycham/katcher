<?php

use League\Flysystem\Adapter\Local;

require __DIR__ . '/../vendor/autoload.php';

/* define dependencies */
$basePath = realpath(__DIR__) . '/../';

$container = new \League\Container\Container();

/* register templates */
$templates = new \League\Plates\Engine("{$basePath}/resources/views");
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


require __DIR__ . '/helpers.php';
require __DIR__ . '/routes.php';


