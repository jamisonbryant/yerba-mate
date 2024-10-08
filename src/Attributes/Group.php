<?php
declare(strict_types=1);

namespace CakeAttributes\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
class Group implements RouteAttribute
{
    /**
     * @param string|null $prefix
     * @param string|null $domain
     * @param string|null $as
     * @param array|null $where
     */
    public function __construct(
        public ?string $prefix = null,
        public ?string $domain = null,
        public ?string $as = null,
        public ?array $where = [],
    ) {
    }
}
