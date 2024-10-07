<?php
declare(strict_types=1);

namespace Cake\Attributes\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class Domain implements RouteAttribute
{
    /**
     * Class constructor.
     *
     * @param string $domain
     */
    public function __construct(
        public string $domain
    ) {
    }
}
