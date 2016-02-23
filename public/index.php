<?php

$basePath = dirname(__DIR__);

require_once "{$basePath}/vendor/autoload.php";

$container = new \League\Container\Container();
$container->delegate(
    new \League\Container\ReflectionContainer()
);

\Katcher\App::start($basePath, $container);

