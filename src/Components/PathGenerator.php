<?php


namespace Katcher\Components;


class PathGenerator
{
    /**
     * @var string
     */
    protected $basePath;

    /**
     * Create PathGenerator
     *
     * @param string $basePath
     */
    public function __construct($basePath)
    {
        $this->basePath = $this->formatBasePath($basePath);
    }

    /**
     * Get base path
     *
     * @return string
     */
    public function basePath()
    {
        return $this->basePath;
    }

    /**
     * Get absolute path
     *
     * @param $relativePath
     * @return string
     */
    public function path($relativePath)
    {
        return $this->basePath . $relativePath;
    }

    /**
     * Format base path
     *
     * @param $basePath
     * @return string
     */
    private function formatBasePath($basePath)
    {
        /* make sure there is a slash at the end */
        if (preg_match('/[\/\\\]$/', $basePath)) {
            return $basePath;
        }

        return $basePath . DIRECTORY_SEPARATOR;
    }
}