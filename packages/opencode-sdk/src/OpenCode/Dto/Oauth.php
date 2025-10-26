<?php

declare(strict_types=1);

namespace ArtisanBuild\OpencodeSdk\OpenCode\Dto;

use Spatie\LaravelData\Data as SpatieData;

class Oauth extends SpatieData
{
    public function __construct(
        public ?string $type = null,
        public ?string $refresh = null,
        public ?string $access = null,
        public int|float|null $expires = null,
    ) {}
}
