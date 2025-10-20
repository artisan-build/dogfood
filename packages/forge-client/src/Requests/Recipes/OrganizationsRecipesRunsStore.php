<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeClient\Requests\Recipes;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * organizations.recipes.runs.store
 *
 * Run a given recipe on a list of servers.
 *
 * Processing mode: <small><code>async</code></small>
 */
class OrganizationsRecipesRunsStore extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

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
        return "/orgs/{$this->organization}/recipes/{$this->recipe}/runs";
    }
}
