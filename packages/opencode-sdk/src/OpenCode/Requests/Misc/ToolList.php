<?php

declare(strict_types=1);

namespace ArtisanBuild\OpencodeSdk\OpenCode\Requests\Misc;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * tool.list
 *
 * List tools with JSON schema parameters for a provider/model
 */
class ToolList extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected ?string $directory,
        protected string $provider,
        protected string $model,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/experimental/tool';
    }

    public function defaultQuery(): array
    {
        return array_filter(['directory' => $this->directory, 'provider' => $this->provider, 'model' => $this->model]);
    }
}
