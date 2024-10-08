<?php
declare(strict_types=1);

namespace CakeAttributes\Test\TestCase\Router;

use Cake\Cache\Cache;
use Cake\Core\Configure;
use Cake\Routing\Route\Route as CakeRoute;
use Cake\Routing\Router;
use Cake\TestSuite\TestCase;
use CakeAttributes\Router\RouteProvider;
use CakeAttributes\Router\ScopedRoute;
use TestApp\Controller\UsersController;

/**
 * Route Provider Test
 *
 * @covers \CakeAttributes\Router\RouteProvider
 */
class RouteProviderTest extends TestCase
{
    protected RouteProvider $provider;
    protected array $configuredRoutes;

    protected function setUp(): void
    {
        parent::setUp();

        // Sets the TestApp namespace to be used instead of App
        $this->setAppNamespace();
        // $this->configApplication(
        //     'TestApp\Application',
        //     [PLUGIN_TESTS . 'test_app' . DS . 'config']
        // );

        $this->provider = new RouteProvider('route_provider_test');
        $this->configuredRoutes = $this->getConfiguredRoutes();
    }

    protected function tearDown(): void
    {
        unset($this->provider);
        unset($this->configuredRoutes);

        parent::tearDown();
    }

    public function testAddRouteUpdatesRoutesArray(): void
    {
        $mockRoute = $this->getMockBuilder(CakeRoute::class)
            ->setConstructorArgs(['/test'])
            ->getMock();

        $routes = $this->provider->addRoute($mockRoute, '/test_scope');

        $routeUris = array_map(fn (ScopedRoute $route) => $route->getUri(), $routes);

        $this->assertContains('/test_scope/test', $routeUris);
    }

    public function testGetRoutesRespectsCacheSetting(): void
    {
        $uncachedRoutes = ['route_1', 'route_2'];
        $cachedRoutes = ['cached_route_1', 'cached_route_2'];

        $mockProvider = $this
            ->getMockBuilder(RouteProvider::class)
            ->setConstructorArgs(['my_test_key'])
            ->onlyMethods(['buildRoutes'])
            ->getMock();

        $mockProvider
            ->expects($this->exactly(1))
            ->method('buildRoutes')
            ->willReturn($uncachedRoutes);

        $clearCache = $mockProvider->getRoutes([UsersController::class], true);
        Cache::write('my_test_key', $cachedRoutes);
        $noClearCache = $mockProvider->getRoutes([UsersController::class]);

        $this->assertEquals($uncachedRoutes, $clearCache);
        $this->assertEquals($cachedRoutes, $noClearCache);
    }

    public function testAutoRegisterRegistersCriticalRoutes(): void
    {
        $routeNames = collection($this->configuredRoutes)
            ->map(fn ($route) => $route->getName())
            ->toArray();

        $this->assertTrue(in_array('attributes.users:authenticate', $routeNames));
//        $this->assertTrue(in_array('partners:search', $routeNames));
//        $this->assertTrue(in_array('organizations:search', $routeNames));
//        $this->assertTrue(in_array('events:search', $routeNames));
//        $this->assertTrue(in_array('swaggerbake.swagger:index', $routeNames));
    }

    public function testAutoRegisterRegistersFallbackRoutesWhenAllowFallbacksIsTrue(): void
    {
        Configure::write('Routing.allowFallbacks', true);
        $routes = $this->getConfiguredRoutes();

        $routeNames = collection($routes)
            ->map(fn ($route) => $route->getName())
            ->toArray();

        $this->assertTrue(in_array('attributes._controller:_action', $routeNames));
    }

    public function testAutoRegisterDoesNotRegisterFallbackRoutesWhenAllowFallbacksIsFalse(): void
    {
        Configure::write('Routing.allowFallbacks', false);
        $routes = $this->getConfiguredRoutes();

        $routeNames = collection($routes)
            ->map(fn ($route) => $route->getName())
            ->toArray();

        $this->assertFalse(in_array('_controller:_action', $routeNames));
    }

    /**
     * @return \Cake\Routing\Route\Route[]
     */
    private function getConfiguredRoutes(): array
    {
        // Reset the router to defaults to that previously-registered routes don't pollute the test
        Router::reload();
        $this->loadPlugins(['CakeAttributes']);

        return Router::getRouteCollection()->routes();
    }
}
