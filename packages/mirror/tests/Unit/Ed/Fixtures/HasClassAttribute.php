<?php

declare(strict_types=1);

namespace ArtisanBuild\Mirror\Tests\Unit\Ed\Fixtures;

use ArtisanBuild\Mirror\Tests\Unit\Ed\Fixtures\Attributes\ClassAttribute;
use ArtisanBuild\Mirror\Tests\Unit\Ed\Fixtures\Attributes\PropertyAttribute;

#[ClassAttribute]
class HasClassAttribute
{
    public string $no_attribute;

    public function __construct(
        #[PropertyAttribute]
        public readonly string $has_attribute = '',
    ) {}
}
