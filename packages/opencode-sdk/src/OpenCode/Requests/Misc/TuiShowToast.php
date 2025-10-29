<?php

declare(strict_types=1);

namespace ArtisanBuild\OpencodeSdk\OpenCode\Requests\Misc;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * tui.showToast
 *
 * Show a toast notification in the TUI
 */
class TuiShowToast extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected ?string $directory = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/tui/show-toast';
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
