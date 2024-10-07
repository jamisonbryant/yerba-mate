<?php
declare(strict_types=1);

namespace Cake\Attributes\Attributes;

use Attribute;
use Cake\Attributes\Enums\HttpMethod;
use Illuminate\Support\Arr;

#[Attribute(Attribute::TARGET_METHOD)]
class Route implements RouteAttribute
{
    public array $methods;

    public array $middleware;

    public function __construct(
        array|string $methods,
        public string $uri,
        public ?string $name = null,
        array|string $middleware = [],
    ) {
        $this->methods = array_map(function (string $verb) {
            $upperVerb = strtoupper($verb);
            if (in_array($upperVerb, HttpMethod::verbs())) {
                return $upperVerb;
            } else {
                return $verb;
            }
        }, Arr::wrap($methods));

        $this->middleware = Arr::wrap($middleware);
    }
}
