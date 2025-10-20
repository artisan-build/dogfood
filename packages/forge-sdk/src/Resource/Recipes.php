<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeSdk\Resource;

use ArtisanBuild\ForgeSdk\Requests\Recipes\ForgeRecipesIndex;
use ArtisanBuild\ForgeSdk\Requests\Recipes\ForgeRecipesRunsStore;
use ArtisanBuild\ForgeSdk\Requests\Recipes\ForgeRecipesShow;
use ArtisanBuild\ForgeSdk\Requests\Recipes\OrganizationRecipesStore;
use ArtisanBuild\ForgeSdk\Requests\Recipes\OrganizationsRecipesDestroy;
use ArtisanBuild\ForgeSdk\Requests\Recipes\OrganizationsRecipesIndex;
use ArtisanBuild\ForgeSdk\Requests\Recipes\OrganizationsRecipesRunsIndex;
use ArtisanBuild\ForgeSdk\Requests\Recipes\OrganizationsRecipesRunsShow;
use ArtisanBuild\ForgeSdk\Requests\Recipes\OrganizationsRecipesRunsStore;
use ArtisanBuild\ForgeSdk\Requests\Recipes\OrganizationsRecipesShow;
use ArtisanBuild\ForgeSdk\Requests\Recipes\OrganizationsRecipesUpdate;
use ArtisanBuild\ForgeSdk\Requests\Recipes\OrganizationsTeamsRecipesDestroy;
use ArtisanBuild\ForgeSdk\Requests\Recipes\OrganizationsTeamsRecipesIndex;
use ArtisanBuild\ForgeSdk\Requests\Recipes\OrganizationsTeamsRecipesStore;
use ArtisanBuild\ForgeSdk\Resource;
use Saloon\Http\Response;

class Recipes extends Resource
{
    /**
     * @param  string  $organization  The organization slug
     * @param  int  $pagesize  The number of results that will be returned per page.
     * @param  string  $pagecursor  The cursor to start the pagination from.
     */
    public function organizationsRecipesIndex(string $organization, ?int $pagesize, ?string $pagecursor): Response
    {
        return $this->connector->send(new OrganizationsRecipesIndex($organization, $pagesize, $pagecursor));
    }

    /**
     * @param  string  $organization  The organization slug
     */
    public function organizationRecipesStore(string $organization): Response
    {
        return $this->connector->send(new OrganizationRecipesStore($organization));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $recipe  The recipe ID
     */
    public function organizationsRecipesShow(string $organization, int $recipe): Response
    {
        return $this->connector->send(new OrganizationsRecipesShow($organization, $recipe));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $recipe  The recipe ID
     */
    public function organizationsRecipesUpdate(string $organization, int $recipe): Response
    {
        return $this->connector->send(new OrganizationsRecipesUpdate($organization, $recipe));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $recipe  The recipe ID
     */
    public function organizationsRecipesDestroy(string $organization, int $recipe): Response
    {
        return $this->connector->send(new OrganizationsRecipesDestroy($organization, $recipe));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $recipe  The recipe ID
     * @param  int  $pagesize  The number of results that will be returned per page.
     * @param  string  $pagecursor  The cursor to start the pagination from.
     */
    public function organizationsRecipesRunsIndex(
        string $organization,
        int $recipe,
        ?int $pagesize,
        ?string $pagecursor,
    ): Response {
        return $this->connector->send(new OrganizationsRecipesRunsIndex($organization, $recipe, $pagesize, $pagecursor));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $recipe  The recipe ID
     */
    public function organizationsRecipesRunsStore(string $organization, int $recipe): Response
    {
        return $this->connector->send(new OrganizationsRecipesRunsStore($organization, $recipe));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $recipe  The recipe ID
     * @param  int  $log  The log ID
     */
    public function organizationsRecipesRunsShow(string $organization, int $recipe, int $log): Response
    {
        return $this->connector->send(new OrganizationsRecipesRunsShow($organization, $recipe, $log));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $team  The team ID
     * @param  int  $pagesize  The number of results that will be returned per page.
     * @param  string  $pagecursor  The cursor to start the pagination from.
     */
    public function organizationsTeamsRecipesIndex(
        string $organization,
        int $team,
        ?int $pagesize,
        ?string $pagecursor,
    ): Response {
        return $this->connector->send(new OrganizationsTeamsRecipesIndex($organization, $team, $pagesize, $pagecursor));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $team  The team ID
     */
    public function organizationsTeamsRecipesStore(string $organization, int $team): Response
    {
        return $this->connector->send(new OrganizationsTeamsRecipesStore($organization, $team));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $team  The team ID
     * @param  int  $recipe  The recipe ID
     */
    public function organizationsTeamsRecipesDestroy(string $organization, int $team, int $recipe): Response
    {
        return $this->connector->send(new OrganizationsTeamsRecipesDestroy($organization, $team, $recipe));
    }

    /**
     * @param  int  $pagesize  The number of results that will be returned per page.
     * @param  string  $pagecursor  The cursor to start the pagination from.
     */
    public function forgeRecipesIndex(?int $pagesize, ?string $pagecursor): Response
    {
        return $this->connector->send(new ForgeRecipesIndex($pagesize, $pagecursor));
    }

    /**
     * @param  int  $forgeRecipe  The forge recipe ID
     */
    public function forgeRecipesShow(int $forgeRecipe): Response
    {
        return $this->connector->send(new ForgeRecipesShow($forgeRecipe));
    }

    /**
     * @param  int  $forgeRecipe  The forge recipe ID
     */
    public function forgeRecipesRunsStore(int $forgeRecipe): Response
    {
        return $this->connector->send(new ForgeRecipesRunsStore($forgeRecipe));
    }
}
