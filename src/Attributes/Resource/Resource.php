<?php
declare(strict_types=1);

namespace Cake\Attributes\Attributes\Resource;

use Attribute;
use Cake\Attributes\Attributes\RouteAttribute;

#[Attribute(Attribute::TARGET_CLASS)]
class Resource implements RouteAttribute
{
    public function __construct(
        public string $resource,
        public bool $apiResource = false,
        public array|string|null $except = null,
        public array|string|null $only = null,
        public array|string|null $names = null,
        public array|string|null $parameters = null,
        public ?bool $shallow = null,
    ) {
    }
}
