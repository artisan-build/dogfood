<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeClient\Requests\Recipes;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * organizations.teams.recipes.destroy
 *
 * Unshare a recipe with a team.
 *
 * Processing mode: <small><code>sync</code></small>
 */
class OrganizationsTeamsRecipesDestroy extends Request
{
    protected Method $method = Method::DELETE;

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $team  The team ID
     * @param  int  $recipe  The recipe ID
     */
    public function __construct(
        protected string $organization,
        protected int $team,
        protected int $recipe,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/orgs/{$this->organization}/teams/{$this->team}/recipes/{$this->recipe}";
    }
}
