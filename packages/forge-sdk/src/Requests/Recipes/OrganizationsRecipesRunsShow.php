<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeSdk\Requests\Recipes;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * organizations.recipes.runs.show
 *
 * Show a specific run for the recipe.
 *
 * Processing mode: <small><code>sync</code></small>
 */
class OrganizationsRecipesRunsShow extends Request
{
    protected Method $method = Method::GET;

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $recipe  The recipe ID
     * @param  int  $log  The log ID
     */
    public function __construct(
        protected string $organization,
        protected int $recipe,
        protected int $log,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/orgs/{$this->organization}/recipes/{$this->recipe}/runs/{$this->log}";
    }
}
