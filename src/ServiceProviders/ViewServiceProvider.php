<?php


namespace Katcher\ServiceProviders;


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
        /** @var AppInterface $app */
        $app = $this->container->get(AppInterface::class);

        $this->container->share('templates', function() use ($app) {
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