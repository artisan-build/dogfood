<?php

declare(strict_types=1);

namespace ArtisanBuild\OpencodeSdk\OpenCode\Dto;

use Spatie\LaravelData\Data as SpatieData;

class Project extends SpatieData
{
    public function __construct(
        public ?string $id = null,
        public ?string $worktree = null,
        public ?string $vcs = null,
        public ?object $time = null,
    ) {}
}
