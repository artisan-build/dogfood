<?php

declare(strict_types=1);

namespace ArtisanBuild\OpencodeSdk\OpenCode\Dto;

use Spatie\LaravelData\Data as SpatieData;

class Time extends SpatieData
{
    public function __construct(
        public int|float|null $created = null,
        public int|float|null $initialized = null,
    ) {}
}
