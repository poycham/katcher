<?php

/**
 * Get container
 *
 * @return \League\Container\Container
 */
function container()
{
    global $container;

    return $container;
}

/**
 * Get template manager
 *
 * @return \League\Plates\Engine
 */
function view()
{
    global $container;

    return $container->get('templates');
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