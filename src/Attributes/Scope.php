<?php

namespace TicketSauce\CakephpRouteAttributes\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class Scope implements RouteAttribute
{
    public function __construct(
        public string $scope
    ) {
    }
}
