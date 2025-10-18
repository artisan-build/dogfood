<?php

declare(strict_types=1);

namespace ArtisanBuild\Till\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class IndividualPlan
{
    public function __construct() {}
}
