<?php

namespace ArtisanBuild\Till\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class UnavailablePlan
{
    public function __construct() {}
}
