<?php

declare(strict_types=1);

namespace Cake\Attributes\Router;

use Cake\Log\Log;
use Cake\Routing\Route\Route as CakeRoute;
use ReflectionClass;
use TicketSauce\CakephpRouteAttributes\Attributes\Defaults;
use TicketSauce\CakephpRouteAttributes\Attributes\Domain;
use TicketSauce\CakephpRouteAttributes\Attributes\Middleware;
use TicketSauce\CakephpRouteAttributes\Attributes\Pattern\Pattern;
use TicketSauce\CakephpRouteAttributes\Attributes\Prefix;
use TicketSauce\CakephpRouteAttributes\Attributes\Route;
use TicketSauce\CakephpRouteAttributes\Attributes\Scope;

/**
 * Route Scanner
 *
 * Scans controllers for methods with the Route attribute and return an array of Route objects.
 *
 * @category Utility
 * @package  App\Service\Router
 * @author   Jamison Bryant <jbryant@ticketsauce.com>
 * @license  Proprietary
 */
class RouteScanner
{
	/**
	 * @var mixed[]
	 */
	private array $classAttributes = [];

	/**
	 * @var mixed[]
	 */
	private array $methodAttributes = [];

	/**
	 * Class constructor.
	 *
	 * @param class-string $controller
	 */
	public function __construct(private readonly string $controller)
	{
		// noop
	}

	/**
	 * Gets the top-level scope for the routes in the controller
	 *
	 * @return string
	 */
	public function getScope(): string
	{
		$scope = '/';
		if (empty($this->classAttributes)) {
			try {
				$this->classAttributes = $this->scanClassAttributes();
			} catch (\ReflectionException $e) {
				Log::error('Unable to scan controller for attributes, defaulting scope to /');
				return $scope;
			}
		}

		/** @var array<Scope> $scopeAttribute */
		$scopeAttribute = array_filter($this->classAttributes, fn ($attrib) => $attrib instanceof Scope);
		if ($scopeAttribute) {
			$scopeAttribute = array_shift($scopeAttribute);
			$scope = $scopeAttribute->scope;
		}

		return $scope;
	}

	/**
	 * Gets the route prefix for the routes in the controller
	 *
	 * @return string|null
	 */
	public function getPrefix(): ?string
	{
		$prefix = null;
		if (empty($this->classAttributes)) {
			try {
				$this->classAttributes = $this->scanClassAttributes();
			} catch (\ReflectionException $e) {
				Log::error('Unable to scan controller for attributes, defaulting prefix to null');
				return null;
			}
		}

		/** @var array<Prefix> $prefixAttribute */
		$prefixAttribute = array_filter($this->classAttributes, fn ($attrib) => $attrib instanceof Prefix);
		if ($prefixAttribute) {
			$prefixAttribute = array_shift($prefixAttribute);
			$prefix = $prefixAttribute->prefix;
		}

		return $prefix;
	}

	/**
	 * @return array<CakeRoute>
	 */
	public function getRoutes(): array
	{
		$routes = [];
		if (empty($this->methodAttributes)) {
			try {
				$this->methodAttributes = $this->scanMethodAttributes();
			} catch (\ReflectionException $e) {
				Log::error('Unable to scan controller actions for attributes, returning empty routes array');
				return [];
			}
		}

		$defaults = ['controller' => $this->getControllerName()];
		$options = [];

		foreach ($this->methodAttributes as $methodName => $attributes) {
			$defaults['action'] = $methodName;
			$patterns = $pass = [];

			$routeAttribute = array_filter($attributes, fn ($attrib) => $attrib instanceof Route);
			if (!$routeAttribute) {
				// There are no route attributes on this method, so skip it
				continue;
			}

			// Route attribute is not repeatable, so we can safely shift it off the array
			$routeAttribute = array_shift($routeAttribute);
			if ($routeAttribute->name) {
				$options['_name'] = $routeAttribute->name;
			}

			/** @var array<Pattern> $patternAttributes */
			$patternAttributes = array_filter($attributes, fn ($attrib) => $attrib instanceof Pattern);
			foreach ($patternAttributes as $pattern) {
				$patterns[$pattern->param] = $pattern->constraint;

				// Add param to pass array so that it gets passed to the controller action
				$pass[] = $pattern->param;
			}

			$defaultAttributes = array_filter($attributes, fn ($attrib) => $attrib instanceof Defaults);
			foreach ($defaultAttributes as $default) {
				$defaults[$default->key] = $default->value;
			}

			$route = (new CakeRoute($routeAttribute->uri, $defaults, $options))
				->setMethods($routeAttribute->methods)
				->setPatterns($patterns)
				->setPass($pass);

			/** @var array<Domain> $domainAttributes */
			$domainAttributes = array_filter($attributes, fn ($attrib) => $attrib instanceof Domain);
			if ($domainAttributes) {
				$host = array_shift($domainAttributes)->domain;
				$route = $route->setHost($host);
			}

			/** @var array<Middleware> $middlewareAttributes */
			$middlewareAttributes = array_filter($attributes, fn ($attrib) => $attrib instanceof Middleware);
			if ($middlewareAttributes) {
				$middleware = array_shift($middlewareAttributes)->middleware;
				$route = $route->setMiddleware($middleware);
			}

			$routes[] = $route;
		}

		return $routes;
	}

	/**
	 * Scans the controller class for class-level attributes using Reflection
	 *
	 * @return mixed[]
	 * @throws \ReflectionException
	 */
	private function scanClassAttributes(): array
	{
		$classReflection = new ReflectionClass($this->controller);

		$attributes = [];
		foreach ($classReflection->getAttributes() as $attribute) {
			$attributes[] = $attribute->newInstance();
		}

		return $attributes;
	}

	/**
	 * Scans the controller class for method-level attributes using Reflection
	 *
	 * @return mixed[]
	 * @throws \ReflectionException
	 */
	private function scanMethodAttributes(): array
	{
		$classReflection = new ReflectionClass($this->controller);

		$attributes = [];
		foreach ($classReflection->getMethods() as $methodReflection) {
			$methodName = $methodReflection->getName();
			$methodAttributes = $methodReflection->getAttributes();

			foreach ($methodAttributes as $attribute) {
				$attributes[$methodName][] = $attribute->newInstance();
			}
		}

		return $attributes;
	}

	/**
	 * @return string
	 */
	private function getControllerName(): string
	{
		$controllerParams = explode('\\', $this->controller);

		return str_replace('Controller', '', array_pop($controllerParams));
	}
}
