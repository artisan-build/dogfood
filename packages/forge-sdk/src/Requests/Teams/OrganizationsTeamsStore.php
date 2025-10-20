<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeSdk\Requests\Teams;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * organizations.teams.store
 *
 * Create a new team for the organization.
 *
 * Processing mode: <small><code>sync</code></small>
 */
class OrganizationsTeamsStore extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    /**
     * @param  string  $organization  The organization slug
     */
    public function __construct(
        protected string $organization,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/orgs/{$this->organization}/teams";
    }
}
