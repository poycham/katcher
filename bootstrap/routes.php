<?php

use League\Route\RouteCollection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

$router = new RouteCollection;

$router->addRoute('GET', '/', 'Katcher\Controllers\KatcherController::index');


$dispatcher = $router->getDispatcher();
$request = Request::createFromGlobals();

$requestURI = preg_replace('/^\/katcher/', '', $request->getPathInfo());
$response = $dispatcher->dispatch($request->getMethod(), $requestURI);

$response->send();