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
        'path_generator'
    ];

    /**
     * Register dependencies
     */
    public function register()
    {
        $this->getContainer()->share('app', function() {
            return App::getInstance();
        });

        $this->getContainer()->share('path_generator', function() {
            $basePath = App::getInstance()->getBasePath();

            return new \Katcher\Components\PathGenerator($basePath);
        });
    }
}