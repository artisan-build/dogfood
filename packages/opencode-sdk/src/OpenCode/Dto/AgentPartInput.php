<?php

declare(strict_types=1);

namespace ArtisanBuild\OpencodeSdk\OpenCode\Dto;

use Spatie\LaravelData\Data as SpatieData;

class AgentPartInput extends SpatieData
{
    public function __construct(
        public ?string $id = null,
        public ?string $type = null,
        public ?string $name = null,
        public ?object $source = null,
    ) {}
}
