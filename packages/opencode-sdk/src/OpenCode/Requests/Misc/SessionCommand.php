<?php

declare(strict_types=1);

namespace ArtisanBuild\OpencodeSdk\OpenCode\Requests\Misc;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * session.command
 *
 * Send a new command to a session
 */
class SessionCommand extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    /**
     * @param  string  $id  Session ID
     */
    public function __construct(
        protected string $id,
        protected ?string $directory = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/session/{$this->id}/command";
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
