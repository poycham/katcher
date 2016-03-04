<?php


namespace Katcher\ServiceProviders;


use League\Container\ServiceProvider\AbstractServiceProvider;
use Symfony\Component\HttpFoundation\Session\Session;

class SessionServiceProvider extends AbstractServiceProvider
{
    /**
     * @var array
     */
    protected $provides = [
        'session',
        'sessionFlash'
    ];

    /**
     * Register dependencies
     */
    public function register()
    {
        $this->container->share('session', function() {
            $session = new Session();
            $session->start();

            return $session;
        });

        $this->container->share('sessionFlash', function() {
            /** @var Session $session */
            $session = $this->container->get('session');

            return $session->getFlashBag();
        });
    }
}