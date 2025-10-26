<?php

declare(strict_types=1);

namespace ArtisanBuild\OpencodeSdk\OpenCode\Dto;

use Spatie\LaravelData\Data as SpatieData;

class Provider extends SpatieData
{
    public function __construct(
        public ?string $api = null,
        public ?string $name = null,
        public ?array $env = null,
        public ?string $id = null,
        public ?string $npm = null,
        public ?object $models = null,
    ) {}
}
