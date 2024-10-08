<?php
declare(strict_types=1);

namespace CakeAttributes\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class Scope implements RouteAttribute
{
    /**
     * Class constructor.
     *
     * @param string $scope
     */
    public function __construct(
        public string $scope
    ) {
    }
}
