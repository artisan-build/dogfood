<?php

declare(strict_types=1);

namespace ArtisanBuild\OpencodeSdk\OpenCode\Requests\Misc;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * session.abort
 *
 * Abort a session
 */
class SessionAbort extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected string $id,
        protected ?string $directory = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/session/{$this->id}/abort";
    }

    public function defaultQuery(): array
    {
        return array_filter(['directory' => $this->directory]);
    }
}
