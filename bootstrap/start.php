<?php

require __DIR__ . '/../vendor/autoload.php';

/* define dependencies */
$basePath = realpath(__DIR__) . '/../';

$container = new \League\Container\Container();

$templates = new \League\Plates\Engine("{$basePath}/resources/views");
$templates->loadExtension(
    new \League\Plates\Extension\Asset("{$basePath}/public", false)
);

$container->add('templates', $templates);

require __DIR__ . '/helpers.php';
require __DIR__ . '/routes.php';


