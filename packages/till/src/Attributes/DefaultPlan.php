<?php

namespace ArtisanBuild\Till\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class DefaultPlan
{
    public function __construct() {}
}
