<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeClient\Requests\BackgroundProcesses;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * organizations.servers.background-processes.destroy
 *
 *
 *
 * Processing mode: <small><code>async</code></small>
 */
class OrganizationsServersBackgroundProcessesDestroy extends Request
{
    protected Method $method = Method::DELETE;

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
        return "/orgs/{$this->organization}/servers/{$this->server}/background-processes/{$this->backgroundProcess}";
    }
}
