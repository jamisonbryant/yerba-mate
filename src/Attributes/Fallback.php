<?php
declare(strict_types=1);

namespace Cake\Attributes\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class Fallback
{
    /**
     * Class constructor.
     */
    public function __construct()
    {
    }
}
