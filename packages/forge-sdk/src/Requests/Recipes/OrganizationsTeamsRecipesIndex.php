<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeSdk\Requests\Recipes;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * organizations.teams.recipes.index
 *
 * Show all recipes for the team.
 *
 * Processing mode: <small><code>sync</code></small>
 */
class OrganizationsTeamsRecipesIndex extends Request
{
    protected Method $method = Method::GET;

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $team  The team ID
     * @param  null|int  $pagesize  The number of results that will be returned per page.
     * @param  null|string  $pagecursor  The cursor to start the pagination from.
     */
    public function __construct(
        protected string $organization,
        protected int $team,
        protected ?int $pagesize = null,
        protected ?string $pagecursor = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/orgs/{$this->organization}/teams/{$this->team}/recipes";
    }

    public function defaultQuery(): array
    {
        return array_filter(['page[size]' => $this->pagesize, 'page[cursor]' => $this->pagecursor]);
    }
}
