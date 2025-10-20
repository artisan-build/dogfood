<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeSdk\Requests\Servers;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * organizations.teams.servers.store
 *
 * Share a server with a team.
 *
 * Processing mode: <small><code>sync</code></small>
 */
class OrganizationsTeamsServersStore extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $team  The team ID
     */
    public function __construct(
        protected string $organization,
        protected int $team,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/orgs/{$this->organization}/teams/{$this->team}/servers";
    }
}
