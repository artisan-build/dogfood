<?php

declare(strict_types=1);

namespace ArtisanBuild\Adverbs\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class Idempotent {}
