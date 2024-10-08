<?php
declare(strict_types=1);

namespace CakeAttributes\Attributes;

use Attribute;
use CakeAttributes\Enums\HttpMethod;

#[Attribute(Attribute::TARGET_METHOD)]
class Route implements RouteAttribute
{
    public array $methods;

    public array $middleware;

    /**
     * Class constructor.
     *
     * @param array|string $methods
     * @param string $uri
     * @param string|null $name
     * @param array|string $middleware
     */
    public function __construct(
        array|string $methods,
        public string $uri,
        public ?string $name = null,
        array|string $middleware = [],
    ) {
        $methods = is_array($methods) ? $methods : [$methods];
        $middlewares = is_array($middleware) ? $middleware : [$middleware];
        $this->methods = array_map(function (string $verb) {
            $upperVerb = strtoupper($verb);
            if (in_array($upperVerb, HttpMethod::verbs())) {
                return $upperVerb;
            } else {
                return $verb;
            }
        }, $methods);

        $this->middleware = $middlewares;
    }
}
