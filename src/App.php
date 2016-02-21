<?php


namespace Katcher;


use Katcher\Components\PathGenerator;
use League\Container\Container;

class App
{
    /**
     * @var string
     */
    protected $basePath;

    /**
     * @var Container
     */
    protected $container;

    protected $providers = [
        \Katcher\ServiceProviders\AppServiceProvider::class,
        \Katcher\ServiceProviders\RoutingServiceProvider::class
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
     * @return string
     */
    public function getBasePath()
    {
        return $this->basePath;
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
     */
    private function addServiceProviders()
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

        self::$instance
            ->addServiceProviders();

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