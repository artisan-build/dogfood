<?php

declare(strict_types=1);

namespace ArtisanBuild\OpencodeSdk\OpenCode\Requests\Misc;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * session.diff
 *
 * Get the diff that resulted from this user message
 */
class SessionDiff extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected string $id,
        protected ?string $directory = null,
        protected ?string $messageId = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/session/{$this->id}/diff";
    }

    public function defaultQuery(): array
    {
        return array_filter(['directory' => $this->directory, 'messageID' => $this->messageId]);
    }
}
