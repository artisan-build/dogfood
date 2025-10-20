<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeClient\Requests\Recipes;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * organizations.recipes.destroy
 *
 * Delete a recipe from the organization.
 *
 * Processing mode: <small><code>sync</code></small>
 */
class OrganizationsRecipesDestroy extends Request
{
    protected Method $method = Method::DELETE;

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $recipe  The recipe ID
     */
    public function __construct(
        protected string $organization,
        protected int $recipe,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/orgs/{$this->organization}/recipes/{$this->recipe}";
    }
}
