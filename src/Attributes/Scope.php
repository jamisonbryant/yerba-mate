<?php
declare(strict_types=1);

namespace Cake\Attributes\Attributes;

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
