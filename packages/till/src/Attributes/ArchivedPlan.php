<?php

namespace ArtisanBuild\Till\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class ArchivedPlan
{
    public function __construct() {}
}
