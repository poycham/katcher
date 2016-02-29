<?php

use Katcher\Controllers\KatcherController;

/** @var \League\Route\RouteCollection $router */
$router->get('/', [KatcherController::class, 'index']);

$router->post('/download-ts', [KatcherController::class, 'downloadTs']);

$router->get('/convert/{folder}', [KatcherController::class, 'showConvert']);
$router->post('/convert/{folder}', [KatcherController::class, 'processConvert']);
/*
$router->addRoute('POST', '/', 'Katcher\Controllers\KatcherController::downloadFiles');

$router->addRoute('GET', '/convert/{folder}', 'Katcher\Controllers\KatcherController::showConvert');

$router->addRoute('GET', '/download/{folder}', 'Katcher\Controllers\KatcherController::download');
$router->addRoute('POST', '/download/{folder}', 'Katcher\Controllers\KatcherController::downloadFile');*/