<?php


namespace Katcher\Controllers;


use Katcher\ServiceLayers\KatcherService;
use League\Plates\Engine;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class KatcherController
{
    /**
     * @var KatcherService
     */
    protected $service;

    public function __construct(KatcherService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request, Response $response)
    {
        $response->setContent(view()->render('index'));

        return $response;
    }

    /**
     * Handle post request to download files
     *
     * @param Request $request
     * @param Response $response
     * @return RedirectResponse
     */
    public function downloadFiles(Request $request, Response $response)
    {
        set_time_limit(0);

        $folder = $this->service->downloadFiles($request->request->all());

        return new RedirectResponse(url("combiner/{$folder}"));
    }

    /**
     * Show combiner page
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function combiner(Request $request, Response $response, array $args)
    {
        $viewData = $this->service->combinerViewData($args['folder']);

        $response->setContent(
            view()->render('combiner', $viewData)
        );

        return $response;
    }

    public function combineFiles(Request $request, Response $response, array $args)
    {
        var_dump($args);

        return $response;
    }

    public function test(Request $request, Response $response)
    {
        $response->setContent(view()->render('index'));

        return $response;
    }
}