<?php


namespace Katcher\ServiceProviders;


use Katcher\App;
use Katcher\AppInterface;
use League\Container\ServiceProvider\AbstractServiceProvider;
use League\Flysystem\Adapter\Local;

class FilesystemServiceProvider extends AbstractServiceProvider
{
    /**
     * @var array
     */
    protected $provides = [
        'filesystem'
    ];

    /**
     * Register dependencies
     */
    public function register()
    {
        /** @var AppInterface $app */
        $app = $this->container->get(AppInterface::class);

        $this->container->share('filesystem', function() use ($app) {
            $adapter = new Local(
                $app->getPath('storage'),
                LOCK_EX,
                Local::DISALLOW_LINKS,
                [
                    'file' => [
                        'public' => 0775,
                        'private' => 0770,
                    ],
                    'dir' => [
                        'public' => 0775,
                        'private' => 0770,
                    ]
                ]
            );

            return new \League\Flysystem\Filesystem($adapter, [
                'visibility' => \League\Flysystem\AdapterInterface::VISIBILITY_PRIVATE
            ]);
        });
    }
}