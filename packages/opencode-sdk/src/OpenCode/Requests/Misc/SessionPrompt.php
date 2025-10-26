<?php

declare(strict_types=1);

namespace ArtisanBuild\OpencodeSdk\OpenCode\Requests\Misc;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * session.prompt
 *
 * Create and send a new message to a session
 */
class SessionPrompt extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    /**
     * @param  string  $id  Session ID
     * @param  string  $prompt  The prompt message to send
     */
    public function __construct(
        protected string $id,
        protected string $prompt,
        protected ?string $directory = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/session/{$this->id}/message";
    }

    public function defaultQuery(): array
    {
        return array_filter(['directory' => $this->directory]);
    }

    public function defaultBody(): array
    {
        return [
            'parts' => [
                [
                    'type' => 'text',
                    'text' => $this->prompt,
                ],
            ],
        ];
    }
}
