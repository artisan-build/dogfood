<?php

declare(strict_types=1);

namespace ArtisanBuild\OpencodeSdk\OpenCode\Dto;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data as SpatieData;

class Session extends SpatieData
{
    public function __construct(
        public ?string $id = null,
        #[MapName('projectID')]
        public ?string $projectId = null,
        public ?string $directory = null,
        #[MapName('parentID')]
        public ?string $parentId = null,
        public ?object $summary = null,
        public ?object $share = null,
        public ?string $title = null,
        public ?string $version = null,
        public ?object $time = null,
        public ?object $revert = null,
    ) {}
}
