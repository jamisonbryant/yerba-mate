<?php

declare(strict_types=1);

namespace Cake\Attributes\Router;

use Cake\Routing\Route\Route;

/**
 * Scoped Route
 *
 * Decorator class for attaching scope information to a route
 *
 * @category Decorator
 * @package  Cake\Attributes\Router
 * @author   Jamison Bryant <jbryant@ticketsauce.com>
 * @license  Proprietary
 */
class ScopedRoute extends Route
{
	/**
	 * Class constructor.
	 */
	public function __construct(private readonly Route $route, protected string $scope)
	{
		parent::__construct($route->template, $route->defaults, $route->options);
	}

	/**
	 * @return string
	 */
	public function getUri(): string
	{
		return $this->scope . $this->route->template;
	}

	/**
	 * @return string
	 */
	public function getScope(): string
	{
		return $this->scope;
	}

	/**
	 * @param string $scope
	 */
	public function setScope(string $scope): void
	{
		$this->scope = $scope;
	}
}
