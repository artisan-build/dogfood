<?php

declare(strict_types=1);

namespace ArtisanBuild\OpencodeSdk\OpenCode\Dto;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data as SpatieData;

class StepStartPart extends SpatieData
{
    public function __construct(
        public ?string $id = null,
        #[MapName('sessionID')]
        public ?string $sessionId = null,
        #[MapName('messageID')]
        public ?string $messageId = null,
        public ?string $type = null,
        public ?string $snapshot = null,
    ) {}
}
