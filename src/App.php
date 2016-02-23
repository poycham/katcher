<?php


namespace Katcher;


use Katcher\Components\PathGenerator;
use League\Container\Container;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Bridge\PsrHttpMessage\Factory\DiactorosFactory;
use Symfony\Bridge\PsrHttpMessage\Factory\HttpFoundationFactory;
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
     * Add service providers
     */
    protected function addServiceProviders()
    {
        foreach ($this->providers as $value) {
            $this->container->addServiceProvider($value);
        }
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