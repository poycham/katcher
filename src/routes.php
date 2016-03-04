<?php

use Katcher\Controllers\KatcherController;

/** @var \League\Route\RouteCollection $router */
$router->get('/', [\Katcher\Controllers\DownloadTsController::class, 'index']);

$router->post('/download-ts', [\Katcher\Controllers\DownloadTsController::class, 'downloadTs']);

$router->get('/convert/{folder}', [KatcherController::class, 'showConvert']);
$router->post('/convert/{folder}', [KatcherController::class, 'convert']);

$router->get('/download-mp4/{folder}', [KatcherController::class, 'showDownloadMp4']);
$router->post('/download-mp4/{folder}', [KatcherController::class, 'downloadMp4']);