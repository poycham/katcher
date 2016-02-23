<?php


namespace Katcher\ServiceProviders;


use League\Container\ServiceProvider\AbstractServiceProvider;
use League\Route\RouteCollection;
use League\Route\Strategy\RequestResponseStrategy;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Bridge\PsrHttpMessage\Factory\DiactorosFactory;
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

            return $router;
        });

        $this->container->share('psr7factory', DiactorosFactory::class);

        $this->getContainer()->share('request', function() {
            /** @var DiactorosFactory $psr7factory */
            $psr7factory = $this->container->get('psr7factory');
            $request = Request::createFromGlobals();
            $psr7request = $psr7factory->createRequest($request);

            return $psr7request;
        });

        $this->getContainer()->share('url_generator', function() {
            /* get base url */
            /* @var ServerRequestInterface $request */
            $request = $this->getContainer()->get('request');

            var_dump($request->getServerParams());
            exit;

            $baseURL = preg_replace(
                '/\/(index.php)?$/',
                '',
                $request->server->get('REDIRECT_URL')
            );

            return new \Katcher\Components\UrlGenerator($baseURL);
        });
    }
}