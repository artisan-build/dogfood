<?php

declare(strict_types=1);

namespace ArtisanBuild\OpencodeSdk\OpenCode\Requests\Misc;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * app.agents
 *
 * List all agents
 */
class AppAgents extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected ?string $directory = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/agent';
    }

    public function defaultQuery(): array
    {
        return array_filter(['directory' => $this->directory]);
    }
}
