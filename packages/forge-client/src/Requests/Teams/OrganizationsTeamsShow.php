<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeClient\Requests\Teams;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * organizations.teams.show
 *
 * Show a specific team for the organization.
 *
 * Processing mode: <small><code>sync</code></small>
 */
class OrganizationsTeamsShow extends Request
{
    protected Method $method = Method::GET;

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
        return "/orgs/{$this->organization}/teams/{$this->team}";
    }
}
