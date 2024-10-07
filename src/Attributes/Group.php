<?php
declare(strict_types=1);

namespace Cake\Attributes\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
class Group implements RouteAttribute
{
    public function __construct(
        public ?string $prefix = null,
        public ?string $domain = null,
        public ?string $as = null,
        public ?array $where = [],
    ) {
    }
}
