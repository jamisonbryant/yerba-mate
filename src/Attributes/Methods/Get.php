<?php
declare(strict_types=1);

namespace Cake\Attributes\Attributes\Methods;

use Attribute;
use Cake\Attributes\Attributes\Route;
use Cake\Attributes\Enums\HttpMethod;

#[Attribute(Attribute::TARGET_METHOD)]
class Get extends Route
{
    public function __construct(
        string $uri,
        ?string $name = null,
        array|string $middleware = [],
    ) {
        parent::__construct(
            methods: [HttpMethod::GET->value],
            uri: $uri,
            name: $name,
            middleware: $middleware,
        );
    }
}
