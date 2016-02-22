<?php


namespace Katcher\ServiceProviders;


use Katcher\App;
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
        $this->container->share('filesystem', function() {
            /** @var \Katcher\Components\PathGenerator $pathGenerator */
            $pathGenerator = $this->container->get('path_generator');

            $adapter = new Local(
                $pathGenerator->path(App::STORAGE_PATH),
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