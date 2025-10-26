<?php

declare(strict_types=1);

namespace ArtisanBuild\OpencodeSdk\OpenCode\Dto;

use Spatie\LaravelData\Data as SpatieData;

class FilePartSourceText extends SpatieData
{
    public function __construct(
        public ?string $value = null,
        public ?int $start = null,
        public ?int $end = null,
    ) {}
}
