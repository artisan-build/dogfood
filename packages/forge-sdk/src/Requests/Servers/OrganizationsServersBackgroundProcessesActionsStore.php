<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeSdk\Requests\Servers;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * organizations.servers.background-processes.actions.store
 *
 * Run an action on a server-level background process.
 *
 * Processing mode:
 * <small><code>async</code></small>
 */
class OrganizationsServersBackgroundProcessesActionsStore extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  int  $backgroundProcess  The background process ID
     */
    public function __construct(
        protected string $organization,
        protected int $server,
        protected int $backgroundProcess,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/orgs/{$this->organization}/servers/{$this->server}/background-processes/{$this->backgroundProcess}/actions";
    }
}
