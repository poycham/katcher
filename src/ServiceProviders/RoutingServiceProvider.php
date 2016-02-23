<?php


namespace Katcher\ServiceProviders;


use League\Container\ServiceProvider\AbstractServiceProvider;
use League\Route\RouteCollection;
use League\Route\Strategy\RequestResponseStrategy;
use Symfony\Component\HttpFoundation\Request;

class RoutingServiceProvider extends AbstractServiceProvider
{
    protected $provides = [
        'router',
        'request',
        'url_generator'
    ];

    public function register()
    {
        $this->getContainer()->share('router', function() {
            $router = new RouteCollection($this->container);
            $router->setStrategy(new RequestResponseStrategy());

            return $router;
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