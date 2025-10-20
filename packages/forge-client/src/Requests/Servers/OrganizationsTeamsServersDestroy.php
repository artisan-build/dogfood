<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeClient\Requests\Servers;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * organizations.teams.servers.destroy
 *
 * Unshare a server with a team.
 *
 * Processing mode: <small><code>sync</code></small>
 */
class OrganizationsTeamsServersDestroy extends Request
{
    protected Method $method = Method::DELETE;

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $team  The team ID
     * @param  int  $server  The server ID
     */
    public function __construct(
        protected string $organization,
        protected int $team,
        protected int $server,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/orgs/{$this->organization}/teams/{$this->team}/servers/{$this->server}";
    }
}
