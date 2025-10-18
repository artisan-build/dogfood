<?php

declare(strict_types=1);

namespace ArtisanBuild\Till\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class TeamPlan
{
    public function __construct() {}
}
