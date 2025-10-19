<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeSdk\Requests\Recipes;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * organizations.recipes.show
 *
 * Show the recipe for the organization.
 *
 * Processing mode: <small><code>sync</code></small>
 */
class OrganizationsRecipesShow extends Request
{
    protected Method $method = Method::GET;

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
