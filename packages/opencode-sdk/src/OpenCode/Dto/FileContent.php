<?php

declare(strict_types=1);

namespace ArtisanBuild\OpencodeSdk\OpenCode\Dto;

use Spatie\LaravelData\Data as SpatieData;

class FileContent extends SpatieData
{
    public function __construct(
        public ?string $type = null,
        public ?string $content = null,
        public ?string $diff = null,
        public ?object $patch = null,
        public ?string $encoding = null,
        public ?string $mimeType = null,
    ) {}
}
