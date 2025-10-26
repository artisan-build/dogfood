<?php

declare(strict_types=1);

namespace ArtisanBuild\OpencodeSdk\OpenCode\Dto;

use Spatie\LaravelData\Data as SpatieData;

class Agent extends SpatieData
{
    public function __construct(
        public ?string $name = null,
        public ?string $description = null,
        public mixed $mode = null,
        public ?bool $builtIn = null,
        public int|float|null $topP = null,
        public int|float|null $temperature = null,
        public ?object $permission = null,
        public ?object $model = null,
        public ?string $prompt = null,
        public ?object $tools = null,
        public ?object $options = null,
    ) {}
}
