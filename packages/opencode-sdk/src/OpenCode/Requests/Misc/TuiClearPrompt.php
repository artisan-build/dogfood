<?php

declare(strict_types=1);

namespace ArtisanBuild\OpencodeSdk\OpenCode\Requests\Misc;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * tui.clearPrompt
 *
 * Clear the prompt
 */
class TuiClearPrompt extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected ?string $directory = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/tui/clear-prompt';
    }

    public function defaultQuery(): array
    {
        return array_filter(['directory' => $this->directory]);
    }

    public function defaultBody(): array
    {
        return [];
    }
}
