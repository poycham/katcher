<?php

use Katcher\Controllers\KatcherController;

/** @var \League\Route\RouteCollection $router */
$router->get('/', [KatcherController::class, 'index']);

$router->post('/download-ts', [KatcherController::class, 'downloadTs']);

$router->get('/convert/{folder}', [KatcherController::class, 'showConvert']);
$router->post('/convert/{folder}', [KatcherController::class, 'processConvert']);

$router->get('/download-mp4/{folder}', [KatcherController::class, 'showDownloadMp4']);
/*
$router->addRoute('POST', '/download/{folder}', 'Katcher\Controllers\KatcherController::downloadFile');*/