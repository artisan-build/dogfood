<?php

declare(strict_types=1);

namespace ArtisanBuild\OpencodeSdk\OpenCode\Dto;

use Spatie\LaravelData\Data as SpatieData;

class ToolListItem extends SpatieData
{
    public function __construct(
        public ?string $id = null,
        public ?string $description = null,
        public mixed $parameters = null,
    ) {}
}
