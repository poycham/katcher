<?php


namespace Katcher\Controllers;


use Katcher\Exceptions\ValidatorException;
use Katcher\ServiceLayers\KatcherService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\RedirectResponse;

class KatcherController extends AbstractController
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