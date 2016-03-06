<?php


namespace Katcher\Controllers;


use Katcher\ServiceLayers\ConvertService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class ConvertController extends AbstractController
{
    /**
     * @var ConvertService
     */
    protected $service;

    public function __construct(ConvertService $service)
    {
        $this->service = $service;
    }

    /**
     * Show convert page
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
        $viewData = $this->service->getShowViewData($args['folder']);

        $response->getBody()->write(
            $this->getView('convert', $viewData)
        );

        return $response;
    }
}