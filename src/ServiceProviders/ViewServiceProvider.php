<?php


namespace Katcher\ServiceProviders;


use Katcher\App;
use League\Container\ServiceProvider\AbstractServiceProvider;

class ViewServiceProvider extends AbstractServiceProvider
{
    /**
     * @var array
     */
    protected $provides = [
        'templates'
    ];

    /**
     * Register dependencies
     */
    public function register()
    {
        $this->getContainer()->share('templates', function() {
            /** @var \Katcher\Components\PathGenerator $pathGenerator */
            $pathGenerator = $this->getContainer()->get('path_generator');

            $templates = new \League\Plates\Engine(
                $pathGenerator->path(App::VIEWS_PATH),
                'tpl.php'
            );
            $templates->loadExtension(
                new \League\Plates\Extension\Asset(
                    $pathGenerator->path(App::PUBLIC_PATH),
                    false
                )
            );

            return $templates;
        });
    }
}