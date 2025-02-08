<?php

namespace ArtisanBuild\Adverbs\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class HandleConfigurationKey
{
    public function __construct(public string $key) {}
}
