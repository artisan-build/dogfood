<?php

declare(strict_types=1);

namespace ArtisanBuild\OpencodeSdk\OpenCode\Dto;

use Spatie\LaravelData\Data as SpatieData;

class ToolStateRunning extends SpatieData
{
    public function __construct(
        public ?string $status = null,
        public mixed $input = null,
        public ?string $title = null,
        public ?object $metadata = null,
        public ?object $time = null,
    ) {}
}
