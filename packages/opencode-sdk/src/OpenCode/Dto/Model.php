<?php

declare(strict_types=1);

namespace ArtisanBuild\OpencodeSdk\OpenCode\Dto;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data as SpatieData;

class Model extends SpatieData
{
    public function __construct(
        public ?string $id = null,
        public ?string $name = null,
        #[MapName('release_date')]
        public ?string $releaseDate = null,
        public ?bool $attachment = null,
        public ?bool $reasoning = null,
        public ?bool $temperature = null,
        #[MapName('tool_call')]
        public ?bool $toolCall = null,
        public ?object $cost = null,
        public ?object $limit = null,
        public ?object $modalities = null,
        public ?bool $experimental = null,
        public ?string $status = null,
        public ?object $options = null,
        public ?object $provider = null,
    ) {}
}
