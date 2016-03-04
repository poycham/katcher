<?php


namespace Katcher\Controllers;


use Katcher\AppInterface;
use Zend\Diactoros\Response\RedirectResponse;

interface ControllerInterface
{
    /**
     * Set app
     *
     * @param AppInterface $app
     */
    public function setApp(AppInterface $app);

    /**
     * Get view
     *
     * @param string $name
     * @param array $data
     * @return string
     */
    public function getView($name, array $data = []);

    /**
     * Get redirect response
     *
     * @param string|UriInterface $uri URI for the Location header.
     * @param int $status Integer status code for the redirect; 302 by default.
     * @param array $headers Array of headers to use at initialization.
     * @return RedirectResponse
     */
    public function getRedirectResponse($uri, $status = 302, array $headers = []);

    /**
     * Set flash
     *
     * @param string $type
     * @param array|string $message
     */
    public function setFlash($type, $message);

    /**
     * Get flash value
     *
     * @param $type
     * @param string $default
     * @return mixed
     */
    public function getFlashValue($type, $default = '');

    /**
     * Get flash array
     *
     * @param $type
     * @return array
     */
    public function getFlashArray($type);
}