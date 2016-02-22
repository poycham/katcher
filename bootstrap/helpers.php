<?php

/**
 * Get app
 *
 * @return \Katcher\App
 */
function app()
{
    return \Katcher\App::getInstance();
}

/**
 * Get container
 *
 * @return \League\Container\Container
 */
function container()
{
    return app()->getContainer();
}

/**
 * Get template manager
 *
 * @return \League\Plates\Engine
 */
function view()
{
    return app()->get('templates');
}

/**
 * Get absolute url
 *
 * @param $uri
 * @return string
 */
function url($uri)
{
    /** @var \Katcher\Components\UrlGenerator $urlGenerator */
    $urlGenerator = container()->get('url_generator');

    return $urlGenerator->url($uri);
}

/**
 * Get absolute base url
 *
 * @return string
 */
function baseURL()
{
    /** @var \Katcher\Components\UrlGenerator $urlGenerator */
    $urlGenerator = container()->get('url_generator');

    return $urlGenerator->baseURL();
}