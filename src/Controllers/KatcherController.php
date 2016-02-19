<?php


namespace Katcher\Controllers;


use Katcher\ServiceLayers\KatcherService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

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
        $redirectURL = url("convert/{$folder}");

        return new RedirectResponse($redirectURL);
    }

    /**
     * Show convert page
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function showConvert(Request $request, Response $response, array $args)
    {
        $viewData = $this->service->combinerViewData($args['folder']);

        $response->setContent(
            view()->render('convert', $viewData)
        );

        return $response;
    }

    /**
     * Handle POST request to convert .ts files to .mp4
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return RedirectResponse
     */
    public function processConvert(Request $request, Response $response, array $args)
    {
        $this->service->convertTsToMp4($args['folder']);

        return new RedirectResponse(url("download/{$args['folder']}"));
    }

    /**
     * Show download page
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function download(Request $request, Response $response, array $args)
    {
        $response->setContent(
            view()->render('download')
        );

        return $response;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function downloadFile(Request $request, Response $response, array $args)
    {
        /* set download headers */
        $fileName = "{$args['folder']}.mp4";
        $contentDisposition = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            $fileName
        );

        $response->headers->set('Content-Type', 'video/mp4');
        $response->headers->set('Content-Disposition', $contentDisposition);

        /* set download content */
        $fileContent = $this->service->getDownloadFileContent($fileName);

        $response->setContent($fileContent);

        return $response;
    }
}