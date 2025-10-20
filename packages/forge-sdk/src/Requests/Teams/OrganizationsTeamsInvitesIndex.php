<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeSdk\Requests\Teams;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * organizations.teams.invites.index
 *
 * Show all pending invitations for the team.
 *
 * Processing mode: <small><code>sync</code></small>
 */
class OrganizationsTeamsInvitesIndex extends Request
{
    protected Method $method = Method::GET;

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $team  The team ID
     * @param  null|string  $include  Available includes are `role`, `roleCount`, `roleExists`, `team`, `teamCount`, `teamExists`, `organization`, `organizationCount`, `organizationExists`. You can include multiple options by separating them with a comma.
     * @param  null|int  $pagesize  The number of results that will be returned per page.
     * @param  null|string  $pagecursor  The cursor to start the pagination from.
     */
    public function __construct(
        protected string $organization,
        protected int $team,
        protected ?string $include = null,
        protected ?int $pagesize = null,
        protected ?string $pagecursor = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/orgs/{$this->organization}/teams/{$this->team}/invites";
    }

    public function defaultQuery(): array
    {
        return array_filter(['include' => $this->include, 'page[size]' => $this->pagesize, 'page[cursor]' => $this->pagecursor]);
    }
}
