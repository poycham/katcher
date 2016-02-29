<?php


namespace Katcher\ServiceProviders;


use Katcher\App;
use League\Container\ServiceProvider\AbstractServiceProvider;

class AppServiceProvider extends AbstractServiceProvider
{
    /**
     * @var array
     */
    protected $provides = [
        \Katcher\AppInterface::class
    ];

    /**
     * Register dependencies
     */
    public function register()
    {
        $this->container->share(\Katcher\AppInterface::class, App::getInstance());
    }
}