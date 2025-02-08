<?php

declare(strict_types=1);

namespace ArtisanBuild\Adverbs\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class Inert
{
    public function __construct() {}
}
