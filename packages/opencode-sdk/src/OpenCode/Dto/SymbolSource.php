<?php

declare(strict_types=1);

namespace ArtisanBuild\OpencodeSdk\OpenCode\Dto;

use Spatie\LaravelData\Data as SpatieData;

class SymbolSource extends SpatieData
{
    public function __construct(
        public ?FilePartSourceText $text = null,
        public ?string $type = null,
        public ?string $path = null,
        public ?Range $range = null,
        public ?string $name = null,
        public ?int $kind = null,
    ) {}
}
