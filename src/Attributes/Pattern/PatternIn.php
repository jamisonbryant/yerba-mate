<?php
declare(strict_types=1);

namespace Cake\Attributes\Attributes\Pattern;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD | Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
class PatternIn extends Pattern
{
    /**
     * Class constructor.
     *
     * @param string $param
     * @param array $constraint
     */
    public function __construct(string $param, array $constraint)
    {
        $this->param = $param;
        $this->constraint = implode('|', $constraint);
    }
}
