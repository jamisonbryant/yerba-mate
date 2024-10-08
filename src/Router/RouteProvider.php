<?php
declare(strict_types=1);

namespace CakeAttributes\Router;

use Cake\Cache\Cache;
use Cake\Core\Configure;
use Cake\Routing\Route\Route as CakeRoute;
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;

/**
 * Route Provider
 *
 * Scans controllers and returns route configuration objects for adding to the route table.
 *
 * @category Utility
 * @package  App\Service\Router
 */
class RouteProvider
{
    /**
     * @var array<\CakeAttributes\Router\ScopedRoute>
     */
    protected array $routes = [];

    /**
     * @var \CakeAttributes\Router\RouteScanner
     */
    protected RouteScanner $routeScanner;

    /**
     * Class constructor.
     *
     * @param string $cacheKey
     * @param string $cacheConfig
     */
    public function __construct(
        protected readonly string $cacheKey = 'attribute_routes',
        protected readonly string $cacheConfig = '_cake_attributes_'
    ) {
        // noop
    }

    /**
     * @param class-string $controller
     * @return array<\CakeAttributes\Router\ScopedRoute>
     */
    public function buildRoutes(string $controller): array
    {
        $this->routeScanner = new RouteScanner($controller);
        $this->routes = [];

        $scope = $this->routeScanner->getScope();
        $routes = $this->routeScanner->getRoutes();

        foreach ($routes as $route) {
            $this->routes[] = new ScopedRoute($route, $scope);
        }

        return $this->routes;
    }

    /**
     * Adds a route to the routes array, with an optional scope
     *
     * @param \Cake\Routing\Route\Route $route
     * @param string $scope
     * @return array<\CakeAttributes\Router\ScopedRoute>
     */
    public function addRoute(CakeRoute $route, string $scope = '/'): array
    {
        $this->routes[] = new ScopedRoute($route, $scope);

        return $this->routes;
    }

    /**
     * Returns the routes array, checking cache first
     *
     * @param array<class-string> $controllers
     * @param bool $clearCache If true, cache will be cleared prior to returning routes
     * @return array<\CakeAttributes\Router\ScopedRoute>
     */
    public function getRoutes(array $controllers, bool $clearCache = false): array
    {
        if ($clearCache === true) {
            Cache::delete($this->cacheKey, $this->cacheConfig);
        }

        return Cache::remember($this->cacheKey, function () use ($controllers) {
            $routes = [];
            foreach ($controllers as $controller) {
                array_push($routes, ...$this->buildRoutes($controller));
            }

            return $routes;
        }, $this->cacheConfig);
    }

    /**
     * Automatically registers identified routes based on reflected attributes
     *
     * @param \Cake\Routing\RouteBuilder $builder
     * @param bool $clearCache
     * @return void
     */
    public function autoRegister(RouteBuilder $builder, bool $clearCache = false): void
    {
        if (Configure::read('Routing.autoRegister') === false) {
            return;
        }

        $controllers = Configure::read('Routing.controllers');
        if (!$controllers) {
            return;
        }

        $routes = collection($this->getRoutes($controllers, $clearCache));
        if ($routes->count() == 0) {
            return;
        }

        // Send the routes to the route builder grouped by scope for efficiency
        //   (this is the last thing we do before CakePHP takes over)
        $routes
            ->groupBy(fn (ScopedRoute $route) => $route->getScope())
            ->each(function (array $scopedRoutes, string $scope) use ($builder): void {
                $scope = Router::normalize("/$scope/");
                $builder->scope($scope, function (RouteBuilder $routes) use ($scopedRoutes): void {
                    foreach ($scopedRoutes as $route) {
                        $routes->connect(
                            Router::normalize("/{$route->template}/"),
                            $route->defaults,
                            $route->options
                        );
                    }
                    if (Configure::read('Routing.allowFallbacks')) {
                        $routes->fallbacks();
                    }
                });
            });
    }
}
