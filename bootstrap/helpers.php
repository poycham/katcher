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