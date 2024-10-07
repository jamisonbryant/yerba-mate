<?php

namespace Cake\Attributes\Attributes\Methods;

use Attribute;
use Cake\Attributes\Attributes\Route;
use Cake\Attributes\Enums\HttpMethod;

#[Attribute(Attribute::TARGET_METHOD)]
class Patch extends Route
{
    public function __construct(
        string $uri,
        ?string $name = null,
        array | string $middleware = [],
    ) {
        parent::__construct(
            methods: [HttpMethod::PATCH->value],
            uri: $uri,
            name: $name,
            middleware: $middleware,
        );
    }
}
