<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeClient\Requests\Recipes;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * organizations.recipes.index
 *
 * Show all recipes for the organization.
 *
 * Processing mode: <small><code>sync</code></small>
 */
class OrganizationsRecipesIndex extends Request
{
    protected Method $method = Method::GET;

    /**
     * @param  string  $organization  The organization slug
     * @param  null|int  $pagesize  The number of results that will be returned per page.
     * @param  null|string  $pagecursor  The cursor to start the pagination from.
     */
    public function __construct(
        protected string $organization,
        protected ?int $pagesize = null,
        protected ?string $pagecursor = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/orgs/{$this->organization}/recipes";
    }

    public function defaultQuery(): array
    {
        return array_filter(['page[size]' => $this->pagesize, 'page[cursor]' => $this->pagecursor]);
    }
}
