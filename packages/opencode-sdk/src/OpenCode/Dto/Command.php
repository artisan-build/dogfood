<?php

declare(strict_types=1);

namespace ArtisanBuild\OpencodeSdk\OpenCode\Dto;

use Spatie\LaravelData\Data as SpatieData;

class Command extends SpatieData
{
    public function __construct(
        public ?string $name = null,
        public ?string $description = null,
        public ?string $agent = null,
        public ?string $model = null,
        public ?string $template = null,
        public ?bool $subtask = null,
    ) {}
}
