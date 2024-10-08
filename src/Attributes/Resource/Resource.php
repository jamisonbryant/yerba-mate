<?php
declare(strict_types=1);

namespace CakeAttributes\Attributes\Resource;

use Attribute;
use CakeAttributes\Attributes\RouteAttribute;

#[Attribute(Attribute::TARGET_CLASS)]
class Resource implements RouteAttribute
{
    /**
     * Class constructor.
     *
     * @param string $resource
     * @param bool $apiResource
     * @param array|string|null $except
     * @param array|string|null $only
     * @param array|string|null $names
     * @param array|string|null $parameters
     * @param bool|null $shallow
     */
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
