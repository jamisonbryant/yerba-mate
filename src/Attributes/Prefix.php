<?php
declare(strict_types=1);

namespace CakeAttributes\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class Prefix implements RouteAttribute
{
    /**
     * Class constructor.
     *
     * @param string $prefix
     */
    public function __construct(
        public string $prefix
    ) {
    }
}
