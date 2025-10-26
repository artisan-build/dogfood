<?php

declare(strict_types=1);

namespace ArtisanBuild\OpencodeSdk\OpenCode\Dto;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data as SpatieData;

class AgentConfig extends SpatieData
{
    public function __construct(
        public ?string $model = null,
        public int|float|null $temperature = null,
        #[MapName('top_p')]
        public int|float|null $topP = null,
        public ?string $prompt = null,
        public ?object $tools = null,
        public ?bool $disable = null,
        public ?string $description = null,
        public mixed $mode = null,
        public ?object $permission = null,
    ) {}
}
