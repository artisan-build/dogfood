<?php

declare(strict_types=1);

namespace ArtisanBuild\OpencodeSdk\OpenCode\Dto;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data as SpatieData;

class ToolPart extends SpatieData
{
    public function __construct(
        public ?string $id = null,
        #[MapName('sessionID')]
        public ?string $sessionId = null,
        #[MapName('messageID')]
        public ?string $messageId = null,
        public ?string $type = null,
        #[MapName('callID')]
        public ?string $callId = null,
        public ?string $tool = null,
        public ?ToolState $state = null,
        public ?object $metadata = null,
    ) {}
}
