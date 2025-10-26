<?php

declare(strict_types=1);

namespace ArtisanBuild\OpencodeSdk\OpenCode\Dto;

use Spatie\LaravelData\Data as SpatieData;

class FilePartInput extends SpatieData
{
    public function __construct(
        public ?string $id = null,
        public ?string $type = null,
        public ?string $mime = null,
        public ?string $filename = null,
        public ?string $url = null,
        public ?FilePartSource $source = null,
    ) {}
}
