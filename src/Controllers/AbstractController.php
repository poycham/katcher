<?php


namespace Katcher\Controllers;


use Katcher\AppInterface;
use Zend\Diactoros\Response\RedirectResponse;

abstract class AbstractController implements ControllerInterface
{
    /**
     * @var AppInterface
     */
    protected $app;

    /**
     * Set app
     *
     * @param AppInterface $app
     */
    public function setApp(AppInterface $app)
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
     * @param string|UriInterface $uri URI for the Location header.
     * @param int $status Integer status code for the redirect; 302 by default.
     * @param array $headers Array of headers to use at initialization.
     * @return RedirectResponse
     */
    public function getRedirectResponse($uri, $status = 302, array $headers = [])
    {
        return new RedirectResponse($uri, $status, $headers);
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
     * Get flash bag
     *
     * @return FlashBagInterface
     */
    protected function getFlashBag()
    {
        return $this->app->get('sessionFlash');
    }

    /**
     * Get flash
     *
     * @param string $type
     * @return array
     */
    protected function getFlash($type)
    {
        return $this->getFlashBag()->get($type);
    }
}