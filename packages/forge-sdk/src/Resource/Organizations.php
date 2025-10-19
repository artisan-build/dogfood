<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeSdk\Resource;

use ArtisanBuild\ForgeSdk\Requests\Organizations\OrganizationsIndex;
use ArtisanBuild\ForgeSdk\Requests\Organizations\OrganizationsServerCredentialsIndex;
use ArtisanBuild\ForgeSdk\Requests\Organizations\OrganizationsServerCredentialsShow;
use ArtisanBuild\ForgeSdk\Requests\Organizations\OrganizationsServerCredentialsVpcsIndex;
use ArtisanBuild\ForgeSdk\Requests\Organizations\OrganizationsServerCredentialsVpcsShow;
use ArtisanBuild\ForgeSdk\Requests\Organizations\OrganizationsServerCredentialsVpcsStore;
use ArtisanBuild\ForgeSdk\Requests\Organizations\OrganizationsShow;
use ArtisanBuild\ForgeSdk\Resource;
use Saloon\Http\Response;

class Organizations extends Resource
{
    /**
     * @param  int  $pagesize  The number of results that will be returned per page.
     * @param  string  $pagecursor  The cursor to start the pagination from.
     */
    public function organizationsIndex(?int $pagesize, ?string $pagecursor): Response
    {
        return $this->connector->send(new OrganizationsIndex($pagesize, $pagecursor));
    }

    /**
     * @param  string  $organization  The organization slug
     */
    public function organizationsShow(string $organization): Response
    {
        return $this->connector->send(new OrganizationsShow($organization));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $pagesize  The number of results that will be returned per page.
     * @param  string  $pagecursor  The cursor to start the pagination from.
     */
    public function organizationsServerCredentialsIndex(
        string $organization,
        ?int $pagesize,
        ?string $pagecursor,
    ): Response {
        return $this->connector->send(new OrganizationsServerCredentialsIndex($organization, $pagesize, $pagecursor));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $credential  The credential ID
     */
    public function organizationsServerCredentialsShow(string $organization, int $credential): Response
    {
        return $this->connector->send(new OrganizationsServerCredentialsShow($organization, $credential));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $credential  The credential ID
     */
    public function organizationsServerCredentialsVpcsIndex(
        string $organization,
        int $credential,
        string $region,
    ): Response {
        return $this->connector->send(new OrganizationsServerCredentialsVpcsIndex($organization, $credential, $region));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $credential  The credential ID
     */
    public function organizationsServerCredentialsVpcsStore(
        string $organization,
        int $credential,
        string $region,
    ): Response {
        return $this->connector->send(new OrganizationsServerCredentialsVpcsStore($organization, $credential, $region));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $credential  The credential ID
     */
    public function organizationsServerCredentialsVpcsShow(
        string $organization,
        int $credential,
        string $region,
        string $vpcId,
    ): Response {
        return $this->connector->send(new OrganizationsServerCredentialsVpcsShow($organization, $credential, $region, $vpcId));
    }
}
