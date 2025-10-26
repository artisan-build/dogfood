<?php

declare(strict_types=1);

namespace ArtisanBuild\OpencodeSdk\OpenCode\Requests\Misc;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * session.get
 *
 * Get session
 */
class SessionGet extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected string $id,
        protected ?string $directory = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/session/{$this->id}";
    }

    public function defaultQuery(): array
    {
        return array_filter(['directory' => $this->directory]);
    }
}
