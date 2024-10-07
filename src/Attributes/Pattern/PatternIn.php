<?php

namespace Cake\Attributes\Attributes\Pattern;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD | Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
class PatternIn extends Pattern
{
    public function __construct(string $param, array $constraint)
    {
        $this->param = $param;
        $this->constraint = implode('|', $constraint);
    }
}
