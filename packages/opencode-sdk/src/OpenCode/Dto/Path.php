<?php

declare(strict_types=1);

namespace ArtisanBuild\OpencodeSdk\OpenCode\Dto;

use Spatie\LaravelData\Data as SpatieData;

class Path extends SpatieData
{
    public function __construct(
        public ?string $state = null,
        public ?string $config = null,
        public ?string $worktree = null,
        public ?string $directory = null,
    ) {}
}
