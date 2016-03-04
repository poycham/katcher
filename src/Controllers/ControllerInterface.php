<?php


namespace Katcher\Controllers;


use Katcher\AppInterface;

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