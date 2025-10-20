<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeClient\Resource;

use ArtisanBuild\ForgeClient\Requests\Nginx\OrganizationsServersNginxTemplatesDestroy;
use ArtisanBuild\ForgeClient\Requests\Nginx\OrganizationsServersNginxTemplatesIndex;
use ArtisanBuild\ForgeClient\Requests\Nginx\OrganizationsServersNginxTemplatesShow;
use ArtisanBuild\ForgeClient\Requests\Nginx\OrganizationsServersNginxTemplatesStore;
use ArtisanBuild\ForgeClient\Requests\Nginx\OrganizationsServersNginxTemplatesUpdate;
use ArtisanBuild\ForgeClient\Resource;
use Saloon\Http\Response;

class Nginx extends Resource
{
    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  string  $sort  Available sorts are `created_at`, `updated_at`, `name`. You can sort by multiple options by separating them with a comma. To sort in descending order, use `-` sign in front of the sort, for example: `-created_at`.
     * @param  int  $pagesize  The number of results that will be returned per page.
     * @param  string  $pagecursor  The cursor to start the pagination from.
     * @param  string  $filtername  The name of the template.
     */
    public function organizationsServersNginxTemplatesIndex(
        string $organization,
        int $server,
        ?string $sort,
        ?int $pagesize,
        ?string $pagecursor,
        ?string $filtername,
    ): Response {
        return $this->connector->send(new OrganizationsServersNginxTemplatesIndex($organization, $server, $sort, $pagesize, $pagecursor, $filtername));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     */
    public function organizationsServersNginxTemplatesStore(string $organization, int $server): Response
    {
        return $this->connector->send(new OrganizationsServersNginxTemplatesStore($organization, $server));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  int  $nginxTemplate  The nginx template ID
     */
    public function organizationsServersNginxTemplatesShow(
        string $organization,
        int $server,
        int $nginxTemplate,
    ): Response {
        return $this->connector->send(new OrganizationsServersNginxTemplatesShow($organization, $server, $nginxTemplate));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  int  $nginxTemplate  The nginx template ID
     */
    public function organizationsServersNginxTemplatesUpdate(
        string $organization,
        int $server,
        int $nginxTemplate,
    ): Response {
        return $this->connector->send(new OrganizationsServersNginxTemplatesUpdate($organization, $server, $nginxTemplate));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  int  $nginxTemplate  The nginx template ID
     */
    public function organizationsServersNginxTemplatesDestroy(
        string $organization,
        int $server,
        int $nginxTemplate,
    ): Response {
        return $this->connector->send(new OrganizationsServersNginxTemplatesDestroy($organization, $server, $nginxTemplate));
    }
}
