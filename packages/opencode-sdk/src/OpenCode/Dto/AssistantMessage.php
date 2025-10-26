<?php

declare(strict_types=1);

namespace ArtisanBuild\OpencodeSdk\OpenCode\Dto;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data as SpatieData;

class AssistantMessage extends SpatieData
{
    public function __construct(
        public ?string $id = null,
        #[MapName('sessionID')]
        public ?string $sessionId = null,
        public ?string $role = null,
        public ?object $time = null,
        public mixed $error = null,
        public ?array $system = null,
        #[MapName('parentID')]
        public ?string $parentId = null,
        #[MapName('modelID')]
        public ?string $modelId = null,
        #[MapName('providerID')]
        public ?string $providerId = null,
        public ?string $mode = null,
        public ?object $path = null,
        public ?bool $summary = null,
        public int|float|null $cost = null,
        public ?object $tokens = null,
    ) {}
}
