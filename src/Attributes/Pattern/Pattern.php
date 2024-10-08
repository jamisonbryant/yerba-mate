<?php
declare(strict_types=1);

namespace CakeAttributes\Attributes\Pattern;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD | Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
class Pattern implements PatternAttribute
{
    /**
     * Class constructor.
     *
     * @param string $param
     * @param string $constraint
     */
    public function __construct(
        public string $param,
        public string $constraint,
    ) {
    }
}
