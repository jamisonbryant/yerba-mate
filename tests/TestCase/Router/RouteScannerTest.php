<?php
declare(strict_types=1);

namespace Cake\Attributes\Test\TestCase\Router;

use Cake\Attributes\Attributes\Prefix;
use Cake\Attributes\Attributes\Scope;
use Cake\Attributes\Router\RouteScanner;
use Cake\Core\Configure;
use Cake\TestSuite\TestCase;
use ReflectionClass;

/**
 * Route Scanner Test
 *
 * @covers \Cake\Attributes\Router\RouteScanner
 */
class RouteScannerTest extends TestCase
{
    /**
     * @dataProvider getControllers
     */
    public function testGetScopeReturnsTheRightScope(string $controller): void
    {
        $classReflection = new ReflectionClass($controller);
        $scanner = new RouteScanner($controller);

        $attributes = [];
        foreach ($classReflection->getAttributes() as $attribute) {
            $attributes[] = $attribute->newInstance();
        }

        $expectedScope = '/';
        $scopeAttribute = array_filter($attributes, fn ($attrib) => $attrib instanceof Scope);
        if ($scopeAttribute) {
            $scopeAttribute = array_shift($scopeAttribute);
            $expectedScope = $scopeAttribute->scope;
        }

        $scope = $scanner->getScope();
        $this->assertEquals($expectedScope, $scope);
    }

    /**
     * @dataProvider getControllers
     */
    public function testGetPrefixReturnsTheRightPrefix(string $controller): void
    {
        $classReflection = new ReflectionClass($controller);
        $scanner = new RouteScanner($controller);

        $attributes = [];
        foreach ($classReflection->getAttributes() as $attribute) {
            $attributes[] = $attribute->newInstance();
        }

        $expectedPrefix = null;
        $prefixAttribute = array_filter($attributes, fn ($attrib) => $attrib instanceof Prefix);
        if ($prefixAttribute) {
            $prefixAttribute = array_shift($prefixAttribute);
            $expectedPrefix = $prefixAttribute->prefix;
        }

        $prefix = $scanner->getPrefix();
        $this->assertEquals($expectedPrefix, $prefix);
    }

    /**
     * @dataProvider getControllers
     */
    public function testGetRoutesReturnsTheSameAmountOfRoutesAsActionsInTheController(string $controller): void
    {
        $classReflection = new ReflectionClass($controller);
        $scanner = new RouteScanner($controller);

        $attributes = [];
        foreach ($classReflection->getMethods() as $methodReflection) {
            $methodName = $methodReflection->getName();
            $methodAttributes = $methodReflection->getAttributes();

            foreach ($methodAttributes as $attribute) {
                $attributes[$methodName][] = $attribute->newInstance();
            }
        }

        $routes = $scanner->getRoutes();
        $this->assertEquals(count($routes), count($attributes));
    }

    public static function getControllers(): array
    {
        $controllers = Configure::read('Routing.controllers');
        $result = [];
        foreach ($controllers as $controller) {
            $result[] = [$controller];
        }

        return $result;
    }
}
