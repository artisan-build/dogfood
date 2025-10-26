<?php

declare(strict_types=1);

namespace ArtisanBuild\OpencodeSdk\OpenCode\Dto;

use Spatie\LaravelData\Data as SpatieData;

class FileSource extends SpatieData
{
    public function __construct(
        public ?FilePartSourceText $text = null,
        public ?string $type = null,
        public ?string $path = null,
    ) {}
}
