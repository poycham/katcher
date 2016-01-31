<?php

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