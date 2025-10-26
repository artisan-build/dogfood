<?php

declare(strict_types=1);

namespace ArtisanBuild\OpencodeSdk\OpenCode\Dto;

use Spatie\LaravelData\Data as SpatieData;

class McpRemoteConfig extends SpatieData
{
    public function __construct(
        public ?string $type = null,
        public ?string $url = null,
        public ?bool $enabled = null,
        public ?object $headers = null,
    ) {}
}
