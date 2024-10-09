<?php
declare(strict_types=1);

namespace CakeAttributes\Routing\Route;
namespace CakeAttributes\Routing\Route;

use Cake\Routing\Route\Route;

/**
 * Decorator class for attaching scope information to a route
 */
class ScopedRoute extends Route
{
    /**
     * Class constructor.
     */
    public function __construct(private readonly Route $route, protected string $scope)
    {
        $options = $route->options + ['routeClass' => self::class];
        parent::__construct($route->template, $route->defaults, $options);
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
    public function getDefinition(): string
    {
        return sprintf('%s | %s | %s', $this->getName(), $this->getUri(), implode(',', $this->defaults['_method']));
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
