<?php

declare(strict_types=1);

namespace ArtisanBuild\OpencodeSdk\OpenCode\Dto;

use Spatie\LaravelData\Data as SpatieData;

class FileDiff extends SpatieData
{
    public function __construct(
        public ?string $file = null,
        public ?string $before = null,
        public ?string $after = null,
        public int|float|null $additions = null,
        public int|float|null $deletions = null,
    ) {}
}
