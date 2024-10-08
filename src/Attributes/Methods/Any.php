<?php
declare(strict_types=1);

namespace CakeAttributes\Attributes\Methods;

use Attribute;
use CakeAttributes\Attributes\Route;
use CakeAttributes\Enums\HttpMethod;

#[Attribute(Attribute::TARGET_METHOD)]
class Any extends Route
{
    /**
     * Class constructor.
     *
     * @param string $uri
     * @param string|null $name
     * @param array|string $middleware
     */
    public function __construct(
        string $uri,
        ?string $name = null,
        array|string $middleware = [],
    ) {
        parent::__construct(
            methods: HttpMethod::verbs(),
            uri: $uri,
            name: $name,
            middleware: $middleware,
        );
    }
}
