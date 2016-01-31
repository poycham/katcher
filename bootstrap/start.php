<?php

require __DIR__ . '/../vendor/autoload.php';

/* define dependencies */
$container = new \League\Container\Container();

$container->add('templates', function() {
    return new \League\Plates\Engine(realpath(__DIR__ . '/../resources/views'));
});

require __DIR__ . '/helpers.php';
require __DIR__ . '/routes.php';


