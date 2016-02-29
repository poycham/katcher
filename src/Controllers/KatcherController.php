<?php


namespace Katcher\Controllers;


use Katcher\ServiceLayers\KatcherService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\RedirectResponse;

class KatcherController
{
    /**
     * @var KatcherService
     */
    protected $service;

    /**
     * Create KatcherController
     *
     * @param KatcherService $service
     */
    public function __construct(KatcherService $service)
    {
        $this->service = $service;
    }

    /**
     * Show index page
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function index(ServerRequestInterface $request, ResponseInterface $response)
    {
        $response->getBody()->write(
            $this->service->getView('index')
        );

        return $response;
    }

    /**
     * Handle post request to download files
     *
     * @param ServerRequestInterface|Request $request
     * @param ResponseInterface|Response $response
     * @return RedirectResponse
     */
    public function downloadTs(ServerRequestInterface $request, ResponseInterface $response)
    {
        set_time_limit(0);

        $folder = $this->service->downloadTs($request->getParsedBody());

        /* redirect to convert page */
        $response = new RedirectResponse(
            url('convert/' . $folder)
        );

        return $response;
    }

    /**
     * Show convert page
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param array $args
     * @return ResponseInterface
     */
    public function showConvert(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        $viewData = $this->service->getConvertViewData($args['folder']);

        $response->getBody()->write(
            $this->service->getView('convert', $viewData)
        );

        return $response;
    }

    /**
     * Handle POST request to convert .ts files to .mp4
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param array $args
     * @return RedirectResponse
     */
    public function processConvert(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        $this->service->convertTsToMp4($args['folder']);

        return $this->service->getRedirectResponse('download/' . $args['folder']);
    }

    /**
     * Show download page
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param array $args
     * @return ResponseInterface
     */
    public function showDownloadMp4(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        $response->getBody()->write(
            $this->service->getView('download-mp4')
        );

        return $response;
    }

    /**
     * Download mp4
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param array $args
     * @return ResponseInterface
     */
    public function downloadMp4(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        /* set download headers */
        $fileName = $args['folder'] . '.mp4';

        $response = $response
            ->withHeader('Content-Type', 'video/mp4')
            ->withHeader('Content-Disposition', 'attachment; filename=' . $fileName . ';');

        /* set download content */
        $response->getBody()->write(
            $this->service->getMp4FileContent($fileName)
        );

        return $response;
    }
}