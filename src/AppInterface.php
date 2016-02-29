<?php


namespace Katcher;


interface AppInterface
{
    /**
     * Get dependency
     *
     * @param $alias
     * @return mixed|object
     */
    public function get($alias);

    /**
     * Get absolute path
     *
     * @param string $relativePath
     * @return string
     */
    public function getPath($relativePath = '');
}