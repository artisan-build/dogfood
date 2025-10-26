<?php

declare(strict_types=1);

namespace ArtisanBuild\OpencodeSdk\OpenCode\Dto;

use Spatie\LaravelData\Data as SpatieData;

class File extends SpatieData
{
    public function __construct(
        public ?string $path = null,
        public ?int $added = null,
        public ?int $removed = null,
        public ?string $status = null,
    ) {}
}
