<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeClient\Requests\Recipes;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * forge-recipes.show
 *
 * Show the Forge recipe.
 *
 * Processing mode: <small><code>sync</code></small>
 */
class ForgeRecipesShow extends Request
{
    protected Method $method = Method::GET;

    /**
     * @param  int  $forgeRecipe  The forge recipe ID
     */
    public function __construct(
        protected int $forgeRecipe,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/forge-recipes/{$this->forgeRecipe}";
    }
}
