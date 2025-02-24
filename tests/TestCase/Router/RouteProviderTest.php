<?php
declare(strict_types=1);

namespace CakeAttributes\Test\TestCase\Router;

use Cake\Console\ConsoleIo;
use Cake\Core\Configure;
use Cake\Core\TestSuite\ContainerStubTrait;
use Cake\Routing\Route\Route as CakeRoute;
use Cake\Routing\RouteCollection;
use Cake\Routing\Router;
use Cake\TestSuite\TestCase;
use CakeAttributes\Routing\Route\ScopedRoute;
use CakeAttributes\Routing\RouteProvider;
use TestApp\Controller\UsersController;

/**
 * Route Provider Test
 *
 * @covers \CakeAttributes\Routing\RouteProvider
 */
class RouteProviderTest extends TestCase
{
    use ContainerStubTrait;

    protected RouteProvider $provider;
    protected array $configuredRoutes;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setAppNamespace();
        $this->configApplication(
            'TestApp\Application',
            [PLUGIN_TESTS . 'test_app' . DS . 'config']
        );

        $this->provider = new RouteProvider('route_provider_test');
        $this->provider->clearCache();
        $this->configuredRoutes = $this->getConfiguredRoutes();
    }

    protected function tearDown(): void
    {
        $this->provider->clearCache();
        unset($this->provider);
        unset($this->configuredRoutes);

        parent::tearDown();
    }

    public function testAddRouteUpdatesRoutesArray(): void
    {
        $cakeRoute = new CakeRoute('/test');
        $routes = $this->provider->addRoute($cakeRoute, '/test_scope');
        $routeUris = array_map(fn (ScopedRoute $route) => $route->getUri(), $routes);
        $this->assertContains('/test_scope/test', $routeUris);
    }

    public function testGetRoutesRespectsCacheSetting(): void
    {
        $firstRun = $this->provider->getRoutes([UsersController::class]);
        $secondRun = $this->provider->getRoutes([UsersController::class]);

        $this->assertEquals($firstRun, $secondRun);
    }

    public function testAutoRegisterScansApplicationControllersForRoutes(): void
    {
        $routeDefinitions = collection($this->configuredRoutes)
            ->map(fn (ScopedRoute $route) => $route->getDefinition())
            ->toArray();

        $expectedRoutes = [
            'users:edit | /users/edit/:id | GET',
            'users:delete | /users/delete/:id | POST',
            'users:add | /users/add | POST',
            'users:view | /users/:id | GET',
            'users:index | /users | GET',
        ];

        foreach ($expectedRoutes as $route) {
            $this->assertTrue( in_array($route, $routeDefinitions, true));
        }
    }

    public function testAutoRegisterScansPluginControllersForRoutes(): void
    {
        $this->printRoutes(Router::getRouteCollection());

        $routeDefinitions = collection($this->configuredRoutes)
            ->map(fn (ScopedRoute $route) => $route->getDefinition())
            ->toArray();

        $expectedRoutes = [
            'cars:edit | /cars/edit/:id | GET',
            'cars:delete | /cars/delete/:id | POST',
            'cars:add | /cars/add | POST',
            'cars:view | /cars/:id | GET',
            'cars:index | /cars | GET',
        ];

        foreach ($expectedRoutes as $route) {
            $this->assertTrue( in_array($route, $routeDefinitions, true));
        }
    }

    public function testAutoRegisterRegistersFallbackRoutesWhenAllowFallbacksIsTrue(): void
    {
        Configure::write('Routing.allowFallbacks', true);
        $this->provider->clearCache();
        $routes = $this->getConfiguredRoutes();

        $routeNames = collection($routes)
            ->map(fn ($route) => $route->getName())
            ->toArray();

        $this->assertTrue(in_array('_controller:_action', $routeNames));
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

        $this->loadPlugins([
            'CakeAttributes',
            'TestAppPlugin' => ['path' => PLUGIN_TESTS . 'test_app/plugins/TestAppPlugin/'],
        ]);

        $this->printRoutes(Router::getRouteCollection());

        $builder = Router::createRouteBuilder('/');
        $this->provider->autoRegister($builder);

        return Router::getRouteCollection()->routes();
    }

    /**
     * @param \Cake\Routing\RouteCollection|null $collection
     * @return void
     */
    private function printRoutes(RouteCollection $collection = null): void
    {
        $io = new ConsoleIo();
        $collection ??= Router::getRouteCollection();

        $routeDefinitions = collection($collection->routes())
            ->map(fn (ScopedRoute $route) => [
                $route->getName(),
                $route->getMethods(),
                $route->getUri(),
            ])
            ->toArray();

        array_unshift($routeDefinitions, ['Name', 'Method(s)', 'URI']);
        $io->helper('table')->output($routeDefinitions);
    }
}
