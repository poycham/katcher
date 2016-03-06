<?php


namespace Katcher\Controllers;


use Katcher\ServiceLayers\DownloadMp4Service;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class DownloadMp4Controller extends AbstractController
{
    /**
     * @var DownloadMp4Service
     */
    protected $service;

    /**
     * Instantiate DownloadMp4Controller
     *
     * @param DownloadMp4Service $service
     */
    public function __construct(DownloadMp4Service $service)
    {
        $this->service = $service;
    }

    /**
     * Show download page
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param array $args
     * @return ResponseInterface
     */
    public function show(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ) {
        $response->getBody()->write(
            $this->getView('download-mp4/show')
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
    public function downloadMp4(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ) {
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