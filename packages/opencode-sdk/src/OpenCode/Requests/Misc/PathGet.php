<?php

declare(strict_types=1);

namespace ArtisanBuild\OpencodeSdk\OpenCode\Requests\Misc;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * path.get
 *
 * Get the current path
 */
class PathGet extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected ?string $directory = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/path';
    }

    public function defaultQuery(): array
    {
        return array_filter(['directory' => $this->directory]);
    }
}
