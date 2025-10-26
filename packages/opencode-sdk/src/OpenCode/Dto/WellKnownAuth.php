<?php

declare(strict_types=1);

namespace ArtisanBuild\OpencodeSdk\OpenCode\Dto;

use Spatie\LaravelData\Data as SpatieData;

class WellKnownAuth extends SpatieData
{
    public function __construct(
        public ?string $type = null,
        public ?string $key = null,
        public ?string $token = null,
    ) {}
}
