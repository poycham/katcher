<?php


namespace Katcher\Controllers;


use League\Plates\Engine;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class KatcherController
{
    public function index(Request $request, Response $response)
    {
        $response->setContent(view()->render('template'));

        return $response;
    }
}