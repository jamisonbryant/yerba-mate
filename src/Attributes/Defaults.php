<?php
declare(strict_types=1);

namespace Cake\Attributes\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD | Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
class Defaults
{
    /**
     * Class constructor.
     *
     * @param string $key
     * @param string $value
     */
    public function __construct(
        public string $key,
        public string $value,
    ) {
    }
}
