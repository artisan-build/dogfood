<?php

declare(strict_types=1);

namespace ArtisanBuild\OpencodeSdk\OpenCode\Dto;

use Spatie\LaravelData\Data as SpatieData;

class BadRequestError extends SpatieData
{
    public function __construct(
        public mixed $data = null,
        public ?array $errors = null,
        public ?bool $success = null,
    ) {}
}
