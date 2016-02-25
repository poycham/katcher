<?php

use Katcher\Controllers\KatcherController;

/** @var \League\Route\RouteCollection $router */
$router->get('/', [KatcherController::class, 'index']);

$router->post('/download-ts', [KatcherController::class, 'downloadTs']);

/*
$router->addRoute('POST', '/', 'Katcher\Controllers\KatcherController::downloadFiles');

$router->addRoute('GET', '/convert/{folder}', 'Katcher\Controllers\KatcherController::showConvert');
$router->addRoute('POST', '/convert/{folder}', 'Katcher\Controllers\KatcherController::processConvert');

$router->addRoute('GET', '/download/{folder}', 'Katcher\Controllers\KatcherController::download');
$router->addRoute('POST', '/download/{folder}', 'Katcher\Controllers\KatcherController::downloadFile');*/