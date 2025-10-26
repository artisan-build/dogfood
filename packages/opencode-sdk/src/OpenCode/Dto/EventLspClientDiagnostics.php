<?php

declare(strict_types=1);

namespace ArtisanBuild\OpencodeSdk\OpenCode\Dto;

use Spatie\LaravelData\Data as SpatieData;

class EventLspClientDiagnostics extends SpatieData
{
    public function __construct(
        public ?string $type = null,
        public ?object $properties = null,
    ) {}
}
