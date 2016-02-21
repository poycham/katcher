<?php


namespace Katcher\ServiceProviders;


use League\Container\ServiceProvider\AbstractServiceProvider;
use League\Route\RouteCollection;
use Symfony\Component\HttpFoundation\Request;

class RoutingServiceProvider extends AbstractServiceProvider
{
    protected $provides = [
        'routes',
        'request',
        'url_generator'
    ];

    public function register()
    {
        $this->getContainer()->share('routes', function() {
            return new RouteCollection($this->getContainer());
        });

        $this->getContainer()->share('request', function() {
            return Request::createFromGlobals();
        });

        $this->getContainer()->share('url_generator', function() {
            /* get base url */
            /* @var Request $request */
            $request = $this->getContainer()->get('request');
            $baseURL = preg_replace(
                '/\/(index.php)?$/',
                '',
                $request->server->get('REDIRECT_URL')
            );

            return new \Katcher\Components\UrlGenerator($baseURL);
        });
    }
}