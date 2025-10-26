<?php

declare(strict_types=1);

namespace ArtisanBuild\OpencodeSdk\OpenCode\Dto;

use Spatie\LaravelData\Data as SpatieData;

class Range extends SpatieData
{
    public function __construct(
        public ?object $start = null,
        public ?object $end = null,
    ) {}
}
