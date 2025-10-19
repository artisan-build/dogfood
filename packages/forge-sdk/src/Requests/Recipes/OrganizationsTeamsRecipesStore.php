<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeSdk\Requests\Recipes;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * organizations.teams.recipes.store
 *
 * Shares a recipe with a team.
 *
 * Processing mode: <small><code>sync</code></small>
 */
class OrganizationsTeamsRecipesStore extends Request implements HasBody
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
        return "/orgs/{$this->organization}/teams/{$this->team}/recipes";
    }
}
