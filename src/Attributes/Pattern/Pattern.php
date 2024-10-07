<?php
declare(strict_types=1);

namespace Cake\Attributes\Attributes\Pattern;

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
