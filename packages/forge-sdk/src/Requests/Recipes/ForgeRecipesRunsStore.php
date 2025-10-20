<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeSdk\Requests\Recipes;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * forge-recipes.runs.store
 *
 * Run a Forge recipe on specified servers.
 *
 * Processing mode: <small><code>async</code></small>
 */
class ForgeRecipesRunsStore extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    /**
     * @param  int  $forgeRecipe  The forge recipe ID
     */
    public function __construct(
        protected int $forgeRecipe,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/forge-recipes/{$this->forgeRecipe}/runs";
    }
}
