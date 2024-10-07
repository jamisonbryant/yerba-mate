<?php
declare(strict_types=1);

namespace Cake\Attributes\Attributes\Pattern;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD | Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
class PatternUuid extends Pattern
{
    public function __construct(string $param)
    {
        $this->param = $param;
        $this->constraint = '[\da-fA-F]{8}-[\da-fA-F]{4}-[\da-fA-F]{4}-[\da-fA-F]{4}-[\da-fA-F]{12}';
    }
}
