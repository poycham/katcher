<?php

use Katcher\Controllers\KatcherController;

/** @var \League\Route\RouteCollection $router */
$router->get('/', [\Katcher\Controllers\DownloadTsController::class, 'index']);
$router->post('/download-ts', [\Katcher\Controllers\DownloadTsController::class, 'downloadTs']);

$router->get('/convert/{folder}', [\Katcher\Controllers\ConvertController::class, 'show']);
$router->post('/convert/{folder}', [\Katcher\Controllers\ConvertController::class, 'convert']);

$router->get('/download-mp4/{folder}', [\Katcher\Controllers\DownloadMp4Controller::class, 'show']);
$router->post('/download-mp4/{folder}', [KatcherController::class, 'downloadMp4']);