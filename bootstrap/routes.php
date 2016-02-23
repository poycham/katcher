<?php

/** @var \League\Route\RouteCollection $router */
$router = app()->get('router');

$router->addRoute('GET', '/', 'Katcher\Controllers\KatcherController::index');
$router->addRoute('POST', '/', 'Katcher\Controllers\KatcherController::downloadFiles');

$router->addRoute('GET', '/convert/{folder}', 'Katcher\Controllers\KatcherController::showConvert');
$router->addRoute('POST', '/convert/{folder}', 'Katcher\Controllers\KatcherController::processConvert');

$router->addRoute('GET', '/download/{folder}', 'Katcher\Controllers\KatcherController::download');
$router->addRoute('POST', '/download/{folder}', 'Katcher\Controllers\KatcherController::downloadFile');