<?php

use League\Flysystem\Adapter\Local;
use Symfony\Component\HttpFoundation\Request;

/* load vendor autoload */
$basePath = dirname(__DIR__);

require_once "{$basePath}/vendor/autoload.php";

/* initialize app */
$app = \Katcher\App::init(
    $basePath,
    new \League\Container\Container()
);

/* load helpers */
require_once $app->getPath(\Katcher\App::HELPERS_PATH);

/* load routes */
require_once $app->getPath(\Katcher\App::ROUTES_PATH);

$app->sendResponse();
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


