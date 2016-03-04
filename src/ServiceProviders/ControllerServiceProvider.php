<?php


namespace Katcher\ServiceProviders;


use League\Container\ServiceProvider\AbstractServiceProvider;
use League\Container\ServiceProvider\BootableServiceProviderInterface;

class ControllerServiceProvider extends AbstractServiceProvider implements BootableServiceProviderInterface
{
    /**
     * Register Dependecies
     */
    public function register()
    {
    }

    /**
     * Boot
     */
    public function boot()
    {
        $this->container->inflector(\Katcher\Controllers\ControllerInterface::class)
            ->invokeMethod('setApp', [$this->container->get(\Katcher\AppInterface::class)]);
    }
}