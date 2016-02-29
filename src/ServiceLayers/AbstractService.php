<?php


namespace Katcher\ServiceLayers;


use Katcher\AppInterface;
use Katcher\Components\UrlGenerator;
use League\Plates\Engine;
use Zend\Diactoros\Response\RedirectResponse;

abstract class AbstractService
{
    protected $app;

    /**
     * Create AbstractService
     *
     * @param AppInterface $app
     */
    public function __construct(AppInterface $app)
    {
        $this->app = $app;
    }

    /**
     * Get view
     *
     * @param string $name
     * @param array $data
     * @return string
     */
    public function getView($name, array $data = [])
    {
        /** @var Engine $templates */
        $templates = $this->app->get('templates');

        return $templates->render($name, $data);
    }

    /**
     * Get redirect response
     *
     * @param $uri
     * @return RedirectResponse
     */
    public function getRedirectResponse($uri)
    {
        /** @var UrlGenerator $urlGenerator */
        $urlGenerator = $this->app->get('url_generator');

        return new RedirectResponse($urlGenerator->url($uri));
    }
}