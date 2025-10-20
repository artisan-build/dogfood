<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeClient\Resource;

use ArtisanBuild\ForgeClient\Requests\Deployments\OrganizationsServersSitesDeploymentsDeployHookShow;
use ArtisanBuild\ForgeClient\Requests\Deployments\OrganizationsServersSitesDeploymentsDeployHookUpdate;
use ArtisanBuild\ForgeClient\Requests\Deployments\OrganizationsServersSitesDeploymentsIndex;
use ArtisanBuild\ForgeClient\Requests\Deployments\OrganizationsServersSitesDeploymentsLogShow;
use ArtisanBuild\ForgeClient\Requests\Deployments\OrganizationsServersSitesDeploymentsPushToDeployDestroy;
use ArtisanBuild\ForgeClient\Requests\Deployments\OrganizationsServersSitesDeploymentsPushToDeployStore;
use ArtisanBuild\ForgeClient\Requests\Deployments\OrganizationsServersSitesDeploymentsScriptShow;
use ArtisanBuild\ForgeClient\Requests\Deployments\OrganizationsServersSitesDeploymentsScriptUpdate;
use ArtisanBuild\ForgeClient\Requests\Deployments\OrganizationsServersSitesDeploymentsShow;
use ArtisanBuild\ForgeClient\Requests\Deployments\OrganizationsServersSitesDeploymentsStatusDestroy;
use ArtisanBuild\ForgeClient\Requests\Deployments\OrganizationsServersSitesDeploymentsStatusShow;
use ArtisanBuild\ForgeClient\Requests\Deployments\OrganizationsServersSitesDeploymentsStore;
use ArtisanBuild\ForgeClient\Requests\Deployments\OrganizationsServersSitesWebhooksDestroy;
use ArtisanBuild\ForgeClient\Requests\Deployments\OrganizationsServersSitesWebhooksIndex;
use ArtisanBuild\ForgeClient\Requests\Deployments\OrganizationsServersSitesWebhooksShow;
use ArtisanBuild\ForgeClient\Requests\Deployments\OrganizationsServersSitesWebhooksStore;
use ArtisanBuild\ForgeClient\Resource;
use Saloon\Http\Response;

class Deployments extends Resource
{
    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  int  $site  The site ID
     * @param  string  $sort  Available sorts are `created_at`, `updated_at`. You can sort by multiple options by separating them with a comma. To sort in descending order, use `-` sign in front of the sort, for example: `-created_at`.
     * @param  int  $pagesize  The number of results that will be returned per page.
     * @param  string  $pagecursor  The cursor to start the pagination from.
     */
    public function organizationsServersSitesWebhooksIndex(
        string $organization,
        int $server,
        int $site,
        ?string $sort,
        ?int $pagesize,
        ?string $pagecursor,
    ): Response {
        return $this->connector->send(new OrganizationsServersSitesWebhooksIndex($organization, $server, $site, $sort, $pagesize, $pagecursor));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  int  $site  The site ID
     */
    public function organizationsServersSitesWebhooksStore(string $organization, int $server, int $site): Response
    {
        return $this->connector->send(new OrganizationsServersSitesWebhooksStore($organization, $server, $site));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  int  $site  The site ID
     * @param  int  $deploymentWebhook  The deployment webhook ID
     */
    public function organizationsServersSitesWebhooksShow(
        string $organization,
        int $server,
        int $site,
        int $deploymentWebhook,
    ): Response {
        return $this->connector->send(new OrganizationsServersSitesWebhooksShow($organization, $server, $site, $deploymentWebhook));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  int  $site  The site ID
     * @param  int  $deploymentWebhook  The deployment webhook ID
     */
    public function organizationsServersSitesWebhooksDestroy(
        string $organization,
        int $server,
        int $site,
        int $deploymentWebhook,
    ): Response {
        return $this->connector->send(new OrganizationsServersSitesWebhooksDestroy($organization, $server, $site, $deploymentWebhook));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  int  $site  The site ID
     * @param  string  $sort  Available sorts are `created_at`. You can sort by multiple options by separating them with a comma. To sort in descending order, use `-` sign in front of the sort, for example: `-created_at`.
     * @param  int  $pagesize  The number of results that will be returned per page.
     * @param  string  $pagecursor  The cursor to start the pagination from.
     * @param  string  $filtercommitHash  The commit hash of the deployment.
     * @param  string  $filtercommitMessage  The commit message of the deployment.
     * @param  string  $filtercommitAuthor  The commit author of the deployment.
     */
    public function organizationsServersSitesDeploymentsIndex(
        string $organization,
        int $server,
        int $site,
        ?string $sort,
        ?int $pagesize,
        ?string $pagecursor,
        ?string $filtercommitHash,
        ?string $filtercommitMessage,
        ?string $filtercommitAuthor,
    ): Response {
        return $this->connector->send(new OrganizationsServersSitesDeploymentsIndex($organization, $server, $site, $sort, $pagesize, $pagecursor, $filtercommitHash, $filtercommitMessage, $filtercommitAuthor));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  int  $site  The site ID
     */
    public function organizationsServersSitesDeploymentsStore(string $organization, int $server, int $site): Response
    {
        return $this->connector->send(new OrganizationsServersSitesDeploymentsStore($organization, $server, $site));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  int  $site  The site ID
     */
    public function organizationsServersSitesDeploymentsStatusShow(
        string $organization,
        int $server,
        int $site,
    ): Response {
        return $this->connector->send(new OrganizationsServersSitesDeploymentsStatusShow($organization, $server, $site));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  int  $site  The site ID
     */
    public function organizationsServersSitesDeploymentsStatusDestroy(
        string $organization,
        int $server,
        int $site,
    ): Response {
        return $this->connector->send(new OrganizationsServersSitesDeploymentsStatusDestroy($organization, $server, $site));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  int  $site  The site ID
     */
    public function organizationsServersSitesDeploymentsScriptShow(
        string $organization,
        int $server,
        int $site,
    ): Response {
        return $this->connector->send(new OrganizationsServersSitesDeploymentsScriptShow($organization, $server, $site));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  int  $site  The site ID
     */
    public function organizationsServersSitesDeploymentsScriptUpdate(
        string $organization,
        int $server,
        int $site,
    ): Response {
        return $this->connector->send(new OrganizationsServersSitesDeploymentsScriptUpdate($organization, $server, $site));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  int  $site  The site ID
     */
    public function organizationsServersSitesDeploymentsDeployHookShow(
        string $organization,
        int $server,
        int $site,
    ): Response {
        return $this->connector->send(new OrganizationsServersSitesDeploymentsDeployHookShow($organization, $server, $site));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  int  $site  The site ID
     */
    public function organizationsServersSitesDeploymentsDeployHookUpdate(
        string $organization,
        int $server,
        int $site,
    ): Response {
        return $this->connector->send(new OrganizationsServersSitesDeploymentsDeployHookUpdate($organization, $server, $site));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  int  $site  The site ID
     */
    public function organizationsServersSitesDeploymentsPushToDeployStore(
        string $organization,
        int $server,
        int $site,
    ): Response {
        return $this->connector->send(new OrganizationsServersSitesDeploymentsPushToDeployStore($organization, $server, $site));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  int  $site  The site ID
     */
    public function organizationsServersSitesDeploymentsPushToDeployDestroy(
        string $organization,
        int $server,
        int $site,
    ): Response {
        return $this->connector->send(new OrganizationsServersSitesDeploymentsPushToDeployDestroy($organization, $server, $site));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  int  $site  The site ID
     * @param  int  $deployment  The deployment ID
     */
    public function organizationsServersSitesDeploymentsShow(
        string $organization,
        int $server,
        int $site,
        int $deployment,
    ): Response {
        return $this->connector->send(new OrganizationsServersSitesDeploymentsShow($organization, $server, $site, $deployment));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  int  $site  The site ID
     * @param  int  $deployment  The deployment ID
     */
    public function organizationsServersSitesDeploymentsLogShow(
        string $organization,
        int $server,
        int $site,
        int $deployment,
    ): Response {
        return $this->connector->send(new OrganizationsServersSitesDeploymentsLogShow($organization, $server, $site, $deployment));
    }
}
