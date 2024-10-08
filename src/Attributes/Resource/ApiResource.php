<?php
declare(strict_types=1);

namespace CakeAttributes\Attributes\Resource;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class ApiResource extends Resource
{
    /**
     * Class constructor.
     *
     * @param string $resource
     * @param array|string|null $except
     * @param array|string|null $only
     * @param array|string|null $names
     * @param array|string|null $parameters
     * @param bool|null $shallow
     */
    public function __construct(
        public string $resource,
        public array|string|null $except = null,
        public array|string|null $only = null,
        public array|string|null $names = null,
        public array|string|null $parameters = null,
        public ?bool $shallow = null,
    ) {
        parent::__construct(
            resource: $resource,
            apiResource: true,
            except: $except,
            only: $only,
            names: $names,
            parameters: $parameters,
            shallow: $shallow,
        );
    }
}
