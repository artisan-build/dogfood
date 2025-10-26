<?php

declare(strict_types=1);

namespace ArtisanBuild\OpencodeSdk\OpenCode\Dto;

use Spatie\LaravelData\Data as SpatieData;

class MessageOutputLengthError extends SpatieData
{
    public function __construct(
        public ?string $name = null,
        public ?object $data = null,
    ) {}
}
