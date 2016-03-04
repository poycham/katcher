<?php


namespace Katcher\ServiceLayers;


use Katcher\AppInterface;
use Katcher\Components\UrlGenerator;
use League\Plates\Engine;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
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
}