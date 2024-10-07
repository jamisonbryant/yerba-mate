<?php
declare(strict_types=1);

namespace Cake\Attributes\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class Scope implements RouteAttribute
{
    public function __construct(
        public string $scope
    ) {
    }
}
