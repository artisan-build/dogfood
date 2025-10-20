<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeClient\Requests\ServerCredentials;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * organizations.teams.server-credentials.destroy
 *
 * Unshare a server credential with a team.
 *
 * Processing mode: <small><code>sync</code></small>
 */
class OrganizationsTeamsServerCredentialsDestroy extends Request
{
    protected Method $method = Method::DELETE;

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $team  The team ID
     * @param  int  $credential  The credential ID
     */
    public function __construct(
        protected string $organization,
        protected int $team,
        protected int $credential,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/orgs/{$this->organization}/teams/{$this->team}/server-credentials/{$this->credential}";
    }
}
