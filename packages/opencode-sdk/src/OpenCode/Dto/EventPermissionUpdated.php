<?php

declare(strict_types=1);

namespace ArtisanBuild\OpencodeSdk\OpenCode\Dto;

use Spatie\LaravelData\Data as SpatieData;

class EventPermissionUpdated extends SpatieData
{
    public function __construct(
        public ?string $type = null,
        public ?Permission $properties = null,
    ) {}
}
