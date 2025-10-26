<?php

declare(strict_types=1);

namespace ArtisanBuild\OpencodeSdk\OpenCode\Dto;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data as SpatieData;

class UserMessage extends SpatieData
{
    public function __construct(
        public ?string $id = null,
        #[MapName('sessionID')]
        public ?string $sessionId = null,
        public ?string $role = null,
        public ?object $time = null,
        public ?object $summary = null,
    ) {}
}
