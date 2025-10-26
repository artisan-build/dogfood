<?php

declare(strict_types=1);

namespace ArtisanBuild\OpencodeSdk\OpenCode\Requests\Misc;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * tui.executeCommand
 *
 * Execute a TUI command (e.g. agent_cycle)
 */
class TuiExecuteCommand extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected ?string $directory = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/tui/execute-command';
    }

    public function defaultQuery(): array
    {
        return array_filter(['directory' => $this->directory]);
    }
}
