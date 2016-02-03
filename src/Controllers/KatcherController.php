<?php


namespace Katcher\Controllers;


use League\Plates\Engine;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class KatcherController
{
    public function __construct()
    {

    }

    public function index(Request $request, Response $response)
    {
        $response->setContent(view()->render('index'));

        return $response;
    }

    public function downloadFiles(Request $request, Response $response)
    {

    }

    public function test(Request $request, Response $response)
    {
        $response->setContent(view()->render('index'));

        return $response;
    }
}