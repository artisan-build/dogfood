<?php

declare(strict_types=1);

namespace ArtisanBuild\OpencodeSdk\OpenCode\Dto;

use Spatie\LaravelData\Data as SpatieData;

class FileNode extends SpatieData
{
    public function __construct(
        public ?string $name = null,
        public ?string $path = null,
        public ?string $absolute = null,
        public ?string $type = null,
        public ?bool $ignored = null,
    ) {}
}
