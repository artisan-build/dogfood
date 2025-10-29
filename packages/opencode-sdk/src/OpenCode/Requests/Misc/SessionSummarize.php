<?php

declare(strict_types=1);

namespace ArtisanBuild\OpencodeSdk\OpenCode\Requests\Misc;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * session.summarize
 *
 * Summarize the session
 */
class SessionSummarize extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    /**
     * @param  string  $id  Session ID
     * @param  string  $providerID  AI provider ID (e.g., 'anthropic', 'openai')
     * @param  string  $modelID  Model ID (e.g., 'claude-sonnet-4-20250514')
     */
    public function __construct(
        protected string $id,
        protected string $providerID,
        protected string $modelID,
        protected ?string $directory = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/session/{$this->id}/summarize";
    }

    public function defaultQuery(): array
    {
        return array_filter(['directory' => $this->directory]);
    }

    public function defaultBody(): array
    {
        return [
            'providerID' => $this->providerID,
            'modelID' => $this->modelID,
        ];
    }
}
