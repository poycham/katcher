<?php


namespace Katcher\Controllers;


use Katcher\Exceptions\ValidatorException;
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

    /**
     * Handle post request to download .ts files
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return RedirectResponse
     */
    public function downloadTs(
        ServerRequestInterface $request,
        ResponseInterface $response
    ) {
        set_time_limit(0);

        $input = $request->getParsedBody();

        try {
            $folder = $this->service->downloadTs($input);
        } catch (ValidatorException $e) {
            $this->setFlash('errors', $e->getErrors());
            $this->setFlash('input', $input);

            return $this->getRedirectResponse('/');
        }

        return $this->getRedirectResponse('/convert/' . $folder);
    }

    /**
     * Handle post request to download missing .ts files
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return RedirectResponse
     */
    public function downloadMissingTs(
        ServerRequestInterface $request,
        ResponseInterface $response
    ) {
        set_time_limit(0);

        $input = $request->getParsedBody();

        $this->service->downloadMissingTs($input['folder']);

        return $this->getRedirectResponse('/convert/' . $input['folder']);
    }
}