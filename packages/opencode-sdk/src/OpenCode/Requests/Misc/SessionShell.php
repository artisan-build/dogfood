<?php

declare(strict_types=1);

namespace ArtisanBuild\OpencodeSdk\OpenCode\Requests\Misc;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * session.shell
 *
 * Run a shell command
 */
class SessionShell extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    /**
     * @param  string  $id  Session ID
     * @param  string  $command  The shell command to execute
     */
    public function __construct(
        protected string $id,
        protected string $command,
        protected ?string $directory = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/session/{$this->id}/shell";
    }

    public function defaultQuery(): array
    {
        return array_filter(['directory' => $this->directory]);
    }

    public function defaultBody(): array
    {
        return [
            'command' => $this->command,
        ];
    }
}
