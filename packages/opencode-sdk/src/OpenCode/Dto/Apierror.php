<?php

declare(strict_types=1);

namespace ArtisanBuild\OpencodeSdk\OpenCode\Dto;

use Spatie\LaravelData\Data as SpatieData;

class Apierror extends SpatieData
{
    public function __construct(
        public ?string $name = null,
        public ?object $data = null,
    ) {}
}
