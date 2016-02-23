<?php


namespace Katcher\ServiceProviders;


use League\Container\ServiceProvider\AbstractServiceProvider;
use League\Route\RouteCollection;
use League\Route\Strategy\RequestResponseStrategy;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Bridge\PsrHttpMessage\Factory\DiactorosFactory;
use Symfony\Bridge\PsrHttpMessage\Factory\HttpFoundationFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RoutingServiceProvider extends AbstractServiceProvider
{
    protected $provides = [
        'router',
        'request',
        'url_generator'
    ];

    public function register()
    {
        $this->container->share('router', function() {
            return new RouteCollection($this->container);
        });

        $this->container->share('psr7factory', DiactorosFactory::class);

        $this->container->share('request', function() {
            /** @var DiactorosFactory $psr7factory */
            $psr7factory = $this->container->get('psr7factory');
            $request = Request::createFromGlobals();

            return $psr7factory->createRequest($request);
        });

        $this->container->share('response', function() {
            /** @var DiactorosFactory $psr7factory */
            $psr7factory = $this->container->get('psr7factory');
            $response = new Response();

            return $psr7factory->createResponse($response);
        });

        $this->container->share('http_foundation_factory', HttpFoundationFactory::class);

        $this->container->share('url_generator', function() {
            /* @var ServerRequestInterface $request */
            $request = $this->container->get('request');
            $server = $request->getServerParams();
            $baseURL = $server['REQUEST_SCHEME'] . '://' . $server['SERVER_NAME'];

            return new \Katcher\Components\UrlGenerator($baseURL);
        });
    }
}