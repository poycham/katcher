<?php


namespace Katcher\Controllers;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class KatcherController
{
    public function index(Request $request, Response $response)
    {
        echo 'controller function haha';

        return $response;
    }
}