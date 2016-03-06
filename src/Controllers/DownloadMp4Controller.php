<?php


namespace Katcher\Controllers;


use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class DownloadMp4Controller extends AbstractController
{
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
}