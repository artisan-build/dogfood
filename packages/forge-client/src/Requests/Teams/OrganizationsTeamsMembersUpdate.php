<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeClient\Requests\Teams;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * organizations.teams.members.update
 *
 * Update the team member for the team.
 *
 * Processing mode: <small><code>sync</code></small>
 */
class OrganizationsTeamsMembersUpdate extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::PUT;

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $team  The team ID
     * @param  int  $user  The user ID
     */
    public function __construct(
        protected string $organization,
        protected int $team,
        protected int $user,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/orgs/{$this->organization}/teams/{$this->team}/members/{$this->user}";
    }
}
