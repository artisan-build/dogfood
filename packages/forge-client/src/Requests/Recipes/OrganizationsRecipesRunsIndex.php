<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeClient\Requests\Recipes;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * organizations.recipes.runs.index
 *
 * Show all runs for the recipe.
 *
 * Processing mode: <small><code>sync</code></small>
 */
class OrganizationsRecipesRunsIndex extends Request
{
    protected Method $method = Method::GET;

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $recipe  The recipe ID
     * @param  null|int  $pagesize  The number of results that will be returned per page.
     * @param  null|string  $pagecursor  The cursor to start the pagination from.
     */
    public function __construct(
        protected string $organization,
        protected int $recipe,
        protected ?int $pagesize = null,
        protected ?string $pagecursor = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/orgs/{$this->organization}/recipes/{$this->recipe}/runs";
    }

    public function defaultQuery(): array
    {
        return array_filter(['page[size]' => $this->pagesize, 'page[cursor]' => $this->pagecursor]);
    }
}
