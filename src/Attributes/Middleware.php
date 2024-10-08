<?php
declare(strict_types=1);

namespace CakeAttributes\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class Middleware implements RouteAttribute
{
    public array $middleware = [];

    /**
     * Class constructor.
     *
     * @param array|string $middleware
     */
    public function __construct(string|array $middleware = [])
    {
        $this->middleware = is_array($middleware) ? $middleware : [$middleware];
    }
}
