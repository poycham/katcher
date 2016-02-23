<?php


namespace Katcher;


use Katcher\Components\PathGenerator;
use League\Container\Container;
use Symfony\Bridge\PsrHttpMessage\Factory\DiactorosFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class App
{
    const PUBLIC_PATH = 'public';
    const STORAGE_PATH = 'storage';
    const VIEWS_PATH = 'resources/views';
    const HELPERS_PATH = 'bootstrap/helpers.php';
    const ROUTES_PATH = 'bootstrap/routes.php';

    /**
     * @var string
     */
    protected $basePath;

    /**
     * @var Container
     */
    protected $container;

    /**
     * @var array
     */
    protected $providers = [
        \Katcher\ServiceProviders\AppServiceProvider::class,
        \Katcher\ServiceProviders\RoutingServiceProvider::class,
        \Katcher\ServiceProviders\ViewServiceProvider::class,
        \Katcher\ServiceProviders\FilesystemServiceProvider::class
    ];

    /**
     * @var App
     */
    protected static $instance;

    /**
     * Create App
     *
     * @param string $basePath
     * @param Container $container
     */
    public function __construct($basePath, Container $container)
    {
        $this->basePath = $basePath;
        $this->container = $container;
    }

    /**
     * Get base path
     *
     * @return string
     */
    public function getBasePath()
    {
        return $this->basePath;
    }

    /**
     * Get container
     *
     * @return Container
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * Get absolute path
     *
     * @param $relativePath
     * @return string
     */
    public function getPath($relativePath)
    {
        return $this->container->get('path_generator')->path($relativePath);
    }

    /**
     * Get dependency
     *
     * @param $alias
     * @return mixed|object
     */
    public function get($alias)
    {
        return $this->container->get($alias);
    }

    /**
     * Send response
     */
    public function sendResponse()
    {
        $this->container->get('url_generator');
        return;

        /** @var \League\Route\RouteCollection $router */
        /** @var \Symfony\Component\HttpFoundation\Request $request */
        /** @var \Symfony\Component\HttpFoundation\Response $response */
        $router = $this->get('router');
        $request = $this->get('request');
        $psr7Request = $this->getPsr7Request($request);
        var_dump($psr7Request->getUri()->getPath());
        exit;
        $dispatcher = $router->getDispatcher(
            $this->getPsr7Request($request)
        );
        $requestURI = preg_replace('/^\/katcher/', '', $request->getPathInfo());

        /*$match = $dispatcher->dispatch($request->getMethod(), $requestURI);
        $response = call_user_func_array($match[1], [$request, new Response(), $match[2]]);*/

        $response->send();
    }

    /**
     * Add service providers
     */
    private function addServiceProviders()
    {
        foreach ($this->providers as $value) {
            $this->container->addServiceProvider($value);
        }
    }

    /**
     * Get psr7 request
     *
     * @param Request $request
     * @return \Psr\Http\Message\ServerRequestInterface|\Zend\Diactoros\ServerRequest
     */
    private function getPsr7Request(Request $request)
    {
        $psr7factory = new DiactorosFactory();

        return $psr7factory->createRequest($request);
    }

    /**
     * Initialize instance
     *
     * @param string $basePath
     * @param Container $container
     * @return App|static
     */
    public static function init($basePath, Container $container)
    {
        self::$instance = new static($basePath, $container);

        self::$instance->addServiceProviders();

        return self::$instance;
    }

    /**
     * Get instance
     *
     * @return App
     */
    public static function getInstance()
    {
        return self::$instance;
    }

    private function __clone() {
        // Stopping Clonning of Object
    }

    private function __wakeup() {
        // Stopping unserialize of object
    }
}