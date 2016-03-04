<?php


namespace Katcher\ServiceLayers;


use Katcher\AppInterface;
use Katcher\Components\UrlGenerator;

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
}