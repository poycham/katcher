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
     * Get flash
     *
     * @param string $type
     * @return array
     */
    public function getFlash($type)
    {
        return $this->getFlashBag()->get($type);
    }

    /**
     * Get flash value
     *
     * @param $type
     * @param string $default
     * @return mixed
     */
    public function getFlashValue($type, $default = '')
    {
        $flash = $this->getFlash($type);

        return (count($flash) > 0) ? $flash[0] : $default;
    }

    /**
     * Get flash array
     *
     * @param $type
     * @return array
     */
    public function getFlashArray($type)
    {
        return $this->getFlashValue($type, []);
    }

    /**
     * Set flash
     *
     * @param string $type
     * @param array|string $message
     */
    public function setFlash($type, $message)
    {
        $this->getFlashBag()->add($type, $message);
    }

    /**
     * Get flash bag
     *
     * @return FlashBagInterface
     */
    private function getFlashBag()
    {
        return $this->app->get('sessionFlash');
    }
}