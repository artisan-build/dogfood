<?php

declare(strict_types=1);

namespace ArtisanBuild\OpencodeSdk\OpenCode\Dto;

use Spatie\LaravelData\Data as SpatieData;

class ToolStatePending extends SpatieData
{
    public function __construct(
        public ?string $status = null,
    ) {}
}
