<?php

declare(strict_types=1);

namespace ArtisanBuild\OpencodeSdk\OpenCode\Dto;

use Spatie\LaravelData\Data as SpatieData;

class TextPartInput extends SpatieData
{
    public function __construct(
        public ?string $id = null,
        public ?string $type = null,
        public ?string $text = null,
        public ?bool $synthetic = null,
        public ?object $time = null,
        public ?object $metadata = null,
    ) {}
}
