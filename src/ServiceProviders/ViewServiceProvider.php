<?php


namespace Katcher\ServiceProviders;


use Katcher\App;
use Katcher\AppInterface;
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
            /** @var AppInterface $app */
            $app = $this->container->get(AppInterface::class);

            $templates = new \League\Plates\Engine(
                $app->getPath('resources/views'),
                'tpl.php'
            );
            $templates->loadExtension(
                new \League\Plates\Extension\Asset(
                    $app->getPath('public'),
                    false
                )
            );

            return $templates;
        });
    }
}