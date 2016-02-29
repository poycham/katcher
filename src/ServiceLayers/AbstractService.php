<?php


namespace Katcher\ServiceLayers;


use Katcher\AppInterface;
use League\Plates\Engine;

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
}