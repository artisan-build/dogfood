<?php

declare(strict_types=1);

namespace ArtisanBuild\OpencodeSdk\OpenCode\Dto;

use Spatie\LaravelData\Data as SpatieData;

class Symbol extends SpatieData
{
    public function __construct(
        public ?string $name = null,
        public int|float|null $kind = null,
        public ?object $location = null,
    ) {}
}
