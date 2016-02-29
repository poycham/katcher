<?php


namespace Katcher;


use League\Container\Container;
use League\Container\ReflectionContainer;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Bridge\PsrHttpMessage\Factory\HttpFoundationFactory;

class App implements AppInterface
{
    const PUBLIC_PATH = 'public';
    const STORAGE_PATH = 'storage';
    const VIEWS_PATH = 'resources/views';
    const HELPERS_PATH = 'src/helpers.php';
    const ROUTES_PATH = 'src/routes.php';

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
     * Add service providers
     *
     * @return $this
     */
    protected function addServiceProviders()
    {
        foreach ($this->providers as $value) {
            $this->container->addServiceProvider($value);
        }

        return $this;
    }

    /**
     * Load helpers
     *
     * @return $this
     */
    protected function loadHelpers()
    {
        require_once $this->getPath(\Katcher\App::HELPERS_PATH);

        return $this;
    }

    /**
     * Define routes
     *
     * @return $this
     */
    protected function defineRoutes()
    {
        $router = $this->get('router');

        include_once $this->getPath(\Katcher\App::ROUTES_PATH);

        return $this;
    }

    /**
     * Send route response
     */
    protected function sendRouteResponse()
    {
        /** @var \League\Route\RouteCollection $router */
        $router = $this->get('router');
        /** @var ServerRequestInterface $psr7Request */
        $psr7Request = $this->get('request');
        $psr7Response = $router->dispatch($psr7Request, $this->get('response'));
        /** @var HttpFoundationFactory $httpFoundationFactory */
        $httpFoundationFactory = $this->get('http_foundation_factory');
        $httpFoundationResponse = $httpFoundationFactory->createResponse($psr7Response);

        $httpFoundationResponse->send();
    }

    /**
     * Start application
     *
     * @param string $basePath
     * @param Container $container
     */
    public static function start($basePath, Container $container)
    {
        self::$instance = new static($basePath, $container);

        self::$instance->addServiceProviders()
            ->loadHelpers()
            ->defineRoutes()
            ->sendRouteResponse();
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