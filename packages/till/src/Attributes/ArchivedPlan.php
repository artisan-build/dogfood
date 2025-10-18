<?php

declare(strict_types=1);

namespace ArtisanBuild\Till\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class ArchivedPlan
{
    public function __construct() {}
}
