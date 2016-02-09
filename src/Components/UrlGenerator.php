<?php


namespace Katcher\Components;


class UrlGenerator
{
    /**
     * @var string
     */
    protected $baseURL;

    /**
     * Create url generator
     *
     * @param string $baseURL
     */
    public function __construct($baseURL)
    {
        $this->baseURL = preg_replace('/\/$/', '', $baseURL);
    }

    /**
     * Get appended uri
     *
     * @param string $uri
     * @return string
     */
    public function url($uri)
    {
        return $this->baseURL . '/' . $uri;
    }

    /**
     * Get base url
     *
     * @return string
     */
    public function baseURL()
    {
        return $this->baseURL;
    }
}