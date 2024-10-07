<?php

namespace TicketSauce\CakephpRouteAttributes\Attributes\Pattern;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD | Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
class Pattern implements PatternAttribute
{
    public function __construct(
        public string $param,
        public string $constraint,
    ) {
    }
}
