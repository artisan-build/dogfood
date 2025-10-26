<?php

declare(strict_types=1);

namespace ArtisanBuild\OpencodeSdk\OpenCode\Dto;

use Spatie\LaravelData\Data as SpatieData;

class Todo extends SpatieData
{
    public function __construct(
        public ?string $content = null,
        public ?string $status = null,
        public ?string $priority = null,
        public ?string $id = null,
    ) {}
}
