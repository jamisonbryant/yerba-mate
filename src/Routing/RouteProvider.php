<?php
declare(strict_types=1);

namespace CakeAttributes\Routing;

use Cake\Cache\Cache;
use Cake\Core\Configure;
use Cake\Routing\Route\Route as CakeRoute;
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;
use CakeAttributes\Routing\Route\ScopedRoute;

/**
 * Scans controllers and returns route configuration objects for adding to the route table.
 */
class RouteProvider
{
    /**
     * @var array<\CakeAttributes\Routing\Route\ScopedRoute>
     */
    protected array $routes = [];

    /**
     * @var \CakeAttributes\Routing\RouteScanner
     */
    protected RouteScanner $routeScanner;

    /**
     * Whether the routes have been cached
     *
     * @var bool
     */
    protected bool $isCached = false;

    /**
     * Class constructor.
     *
     * @param string $cacheKey
     * @param string $cacheConfig
     */
    public function __construct(
        protected readonly string $cacheKey = 'attribute_routes',
        protected readonly string $cacheConfig = 'cake_attributes'
    ) {
        // noop
    }

    /**
     * @param class-string $controller
     * @return array<\CakeAttributes\Routing\Route\ScopedRoute>
     */
    public function buildRoutes(string $controller): array
    {
        $this->routeScanner = new RouteScanner($controller);

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
     * @return array<\CakeAttributes\Routing\Route\ScopedRoute>
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
     * @return array<\CakeAttributes\Routing\Route\ScopedRoute>
     */
    public function getRoutes(array $controllers): array
    {
        $cachedRoutes = Cache::read($this->cacheKey, $this->cacheConfig);
        if ($cachedRoutes) {
            $this->isCached = true;
            $this->routes = $cachedRoutes;

            return $cachedRoutes;
        }

        $routes = [];
        foreach ($controllers as $controller) {
            array_push($routes, ...$this->buildRoutes($controller));
        }

        return $routes;
    }

    /**
     * @return void
     */
    public function clearCache(): void
    {
        $this->isCached = false;
        Cache::delete($this->cacheKey, $this->cacheConfig);
    }

    /**
     * Automatically registers identified routes based on reflected attributes
     *
     * @param \Cake\Routing\RouteBuilder $builder
     * @return void
     */
    public function autoRegister(RouteBuilder $builder): void
    {
        if (Configure::read('Routing.autoRegister') === false) {
            return;
        }

        $controllers = Configure::read('Routing.controllers');
        if (!$controllers) {
            return;
        }

        $routes = collection($this->getRoutes($controllers));
        if ($routes->isEmpty()) {
            return;
        }

        if ($this->isCached) {
            foreach ($routes as $route) {
                Router::getRouteCollection()->add($route);
            }

            return;
        }

        $toBeCachedRoutes = [];

        // Send the routes to the route builder grouped by scope for efficiency
        //   (this is the last thing we do before CakePHP takes over)
        $routes
            ->groupBy(fn (ScopedRoute $route) => $route->getScope())
            ->each(function (array $scopedRoutes, string $scope) use ($builder, &$toBeCachedRoutes): void {
                $scope = Router::normalize("/$scope/");
                $builder->scope($scope, function (RouteBuilder $routes) use ($scopedRoutes, &$toBeCachedRoutes): void {
                    foreach ($scopedRoutes as $route) {
                        $toBeCachedRoutes[] = $routes->connect(
                            $route,
                            $route->defaults,
                            $route->options
                        );
                    }
                    if (Configure::read('Routing.allowFallbacks')) {
                        $routes->fallbacks();
                    }
                });
            });

        /** @var array<\CakeAttributes\Routing\Route\ScopedRoute> $toBeCachedRoutes */
        $this->routes = $toBeCachedRoutes;
        Cache::write($this->cacheKey, $toBeCachedRoutes, $this->cacheConfig);
    }
}
