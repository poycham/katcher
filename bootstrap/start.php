<?php

use League\Flysystem\Adapter\Local;
use Symfony\Component\HttpFoundation\Request;

require __DIR__ . '/../vendor/autoload.php';

$app = \Katcher\App::init(
    dirname(__DIR__),
    new \League\Container\Container()
);

var_dump($app->get('filesystem'));

exit;

/* define dependencies */
$basePath = realpath(__DIR__) . '/../';

$container = new \League\Container\Container();


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


require __DIR__ . '/helpers.php';
require __DIR__ . '/routes.php';


