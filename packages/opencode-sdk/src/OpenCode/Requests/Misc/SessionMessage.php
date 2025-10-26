<?php

declare(strict_types=1);

namespace ArtisanBuild\OpencodeSdk\OpenCode\Requests\Misc;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * session.message
 *
 * Get a message from a session
 */
class SessionMessage extends Request
{
    protected Method $method = Method::GET;

    /**
     * @param  string  $id  Session ID
     * @param  string  $messageId  Message ID
     */
    public function __construct(
        protected string $id,
        protected string $messageId,
        protected ?string $directory = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/session/{$this->id}/message/{$this->messageId}";
    }

    public function defaultQuery(): array
    {
        return array_filter(['directory' => $this->directory]);
    }
}
