<?php

declare(strict_types=1);

namespace ArtisanBuild\OpencodeSdk\OpenCode\Dto;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data as SpatieData;

class Permission extends SpatieData
{
    public function __construct(
        public ?string $id = null,
        public ?string $type = null,
        public mixed $pattern = null,
        #[MapName('sessionID')]
        public ?string $sessionId = null,
        #[MapName('messageID')]
        public ?string $messageId = null,
        #[MapName('callID')]
        public ?string $callId = null,
        public ?string $title = null,
        public ?object $metadata = null,
        public ?object $time = null,
    ) {}
}
