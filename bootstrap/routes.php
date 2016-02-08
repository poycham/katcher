<?php

use League\Route\RouteCollection;
use Symfony\Component\HttpFoundation\Request;

$router = new RouteCollection($container);

$router->addRoute('GET', '/', 'Katcher\Controllers\KatcherController::index');

$router->addRoute('POST', '/', 'Katcher\Controllers\KatcherController::downloadFiles');

$router->addRoute('GET', '/combiner/{format}', 'Katcher\Controllers\KatcherController::combiner');

$router->addRoute('GET', '/test/new', 'Katcher\Controllers\KatcherController::test');


$dispatcher = $router->getDispatcher();
$request = Request::createFromGlobals();

$requestURI = preg_replace('/^\/katcher/', '', $request->getPathInfo());
$response = $dispatcher->dispatch($request->getMethod(), $requestURI);

$response->send();