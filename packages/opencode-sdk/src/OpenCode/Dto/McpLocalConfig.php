<?php

declare(strict_types=1);

namespace ArtisanBuild\OpencodeSdk\OpenCode\Dto;

use Spatie\LaravelData\Data as SpatieData;

class McpLocalConfig extends SpatieData
{
    public function __construct(
        public ?string $type = null,
        public ?array $command = null,
        public ?object $environment = null,
        public ?bool $enabled = null,
    ) {}
}
