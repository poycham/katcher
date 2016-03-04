<?php


namespace Katcher\Controllers;


use Katcher\ServiceLayers\DownloadTsService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class DownloadTsController extends AbstractController
{
    /**
     * @var DownloadTsService
     */
    protected $service;

    /**
     * Create DownloadTsController
     *
     * @param DownloadTsService $service
     */
    public function __construct(DownloadTsService $service)
    {
        $this->service = $service;
    }

    /**
     * Show download ts page
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function index(
        ServerRequestInterface $request,
        ResponseInterface $response
    ) {
        $viewData = $this->service->getIndexViewData([
            'input' => $this->getFlashArray('input'),
            'errors' => $this->getFlashArray('errors')
        ]);

        $response->getBody()->write(
            $this->getView('download-ts/index', $viewData)
        );

        return $response;
    }
}