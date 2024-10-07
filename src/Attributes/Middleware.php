<?php
declare(strict_types=1);

namespace Cake\Attributes\Attributes;

use Attribute;
use Illuminate\Support\Arr;

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
        $this->middleware = Arr::wrap($middleware);
    }
}
