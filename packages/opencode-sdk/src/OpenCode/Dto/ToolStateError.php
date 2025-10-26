<?php

declare(strict_types=1);

namespace ArtisanBuild\OpencodeSdk\OpenCode\Dto;

use Spatie\LaravelData\Data as SpatieData;

class ToolStateError extends SpatieData
{
    public function __construct(
        public ?string $status = null,
        public ?object $input = null,
        public ?string $error = null,
        public ?object $metadata = null,
        public ?object $time = null,
    ) {}
}
