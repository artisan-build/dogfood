<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeSdk\Resource;

use ArtisanBuild\ForgeSdk\Requests\Sites\OrganizationsServersSitesComposerCredentialsDestroy;
use ArtisanBuild\ForgeSdk\Requests\Sites\OrganizationsServersSitesComposerCredentialsIndex;
use ArtisanBuild\ForgeSdk\Requests\Sites\OrganizationsServersSitesComposerCredentialsShow;
use ArtisanBuild\ForgeSdk\Requests\Sites\OrganizationsServersSitesComposerCredentialsStore;
use ArtisanBuild\ForgeSdk\Requests\Sites\OrganizationsServersSitesComposerCredentialsUpdate;
use ArtisanBuild\ForgeSdk\Requests\Sites\OrganizationsServersSitesDestroy;
use ArtisanBuild\ForgeSdk\Requests\Sites\OrganizationsServersSitesDomainsActionsStore;
use ArtisanBuild\ForgeSdk\Requests\Sites\OrganizationsServersSitesDomainsCertificateDestroy;
use ArtisanBuild\ForgeSdk\Requests\Sites\OrganizationsServersSitesDomainsCertificateShow;
use ArtisanBuild\ForgeSdk\Requests\Sites\OrganizationsServersSitesDomainsCertificateStore;
use ArtisanBuild\ForgeSdk\Requests\Sites\OrganizationsServersSitesDomainsConfigurations;
use ArtisanBuild\ForgeSdk\Requests\Sites\OrganizationsServersSitesDomainsDestroy;
use ArtisanBuild\ForgeSdk\Requests\Sites\OrganizationsServersSitesDomainsIndex;
use ArtisanBuild\ForgeSdk\Requests\Sites\OrganizationsServersSitesDomainsShow;
use ArtisanBuild\ForgeSdk\Requests\Sites\OrganizationsServersSitesDomainsStore;
use ArtisanBuild\ForgeSdk\Requests\Sites\OrganizationsServersSitesDomainsUpdate;
use ArtisanBuild\ForgeSdk\Requests\Sites\OrganizationsServersSitesEnvironmentShow;
use ArtisanBuild\ForgeSdk\Requests\Sites\OrganizationsServersSitesEnvironmentUpdate;
use ArtisanBuild\ForgeSdk\Requests\Sites\OrganizationsServersSitesHealthcheckShow;
use ArtisanBuild\ForgeSdk\Requests\Sites\OrganizationsServersSitesHealthcheckUpdate;
use ArtisanBuild\ForgeSdk\Requests\Sites\OrganizationsServersSitesHeartbeatsDestroy;
use ArtisanBuild\ForgeSdk\Requests\Sites\OrganizationsServersSitesHeartbeatsIndex;
use ArtisanBuild\ForgeSdk\Requests\Sites\OrganizationsServersSitesHeartbeatsShow;
use ArtisanBuild\ForgeSdk\Requests\Sites\OrganizationsServersSitesHeartbeatsStore;
use ArtisanBuild\ForgeSdk\Requests\Sites\OrganizationsServersSitesHeartbeatsUpdate;
use ArtisanBuild\ForgeSdk\Requests\Sites\OrganizationsServersSitesIndex;
use ArtisanBuild\ForgeSdk\Requests\Sites\OrganizationsServersSitesLoadBalancingNodesIndex;
use ArtisanBuild\ForgeSdk\Requests\Sites\OrganizationsServersSitesLoadBalancingNodesUpdate;
use ArtisanBuild\ForgeSdk\Requests\Sites\OrganizationsServersSitesLogsApplicationDestroy;
use ArtisanBuild\ForgeSdk\Requests\Sites\OrganizationsServersSitesLogsApplicationShow;
use ArtisanBuild\ForgeSdk\Requests\Sites\OrganizationsServersSitesLogsNginxAccessDestroy;
use ArtisanBuild\ForgeSdk\Requests\Sites\OrganizationsServersSitesLogsNginxAccessShow;
use ArtisanBuild\ForgeSdk\Requests\Sites\OrganizationsServersSitesLogsNginxErrorDestroy;
use ArtisanBuild\ForgeSdk\Requests\Sites\OrganizationsServersSitesLogsNginxErrorShow;
use ArtisanBuild\ForgeSdk\Requests\Sites\OrganizationsServersSitesNginxShow;
use ArtisanBuild\ForgeSdk\Requests\Sites\OrganizationsServersSitesNginxUpdate;
use ArtisanBuild\ForgeSdk\Requests\Sites\OrganizationsServersSitesStore;
use ArtisanBuild\ForgeSdk\Requests\Sites\OrganizationsServersSitesUpdate;
use ArtisanBuild\ForgeSdk\Requests\Sites\OrganizationsSitesIndex;
use ArtisanBuild\ForgeSdk\Requests\Sites\OrganizationsSitesShow;
use ArtisanBuild\ForgeSdk\Requests\Sites\SitesIndex;
use ArtisanBuild\ForgeSdk\Resource;
use Saloon\Http\Response;

class Sites extends Resource
{
    /**
     * @param  string  $include  Available includes are `tags`, `tagsCount`, `tagsExists`, `latestDeployment`, `latestDeploymentCount`, `latestDeploymentExists`. You can include multiple options by separating them with a comma.
     * @param  int  $pagesize  The number of results that will be returned per page.
     * @param  string  $pagecursor  The cursor to start the pagination from.
     */
    public function sitesIndex(?string $include, ?int $pagesize, ?string $pagecursor, ?string $filtername): Response
    {
        return $this->connector->send(new SitesIndex($include, $pagesize, $pagecursor, $filtername));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  string  $include  Available includes are `tags`, `tagsCount`, `tagsExists`, `latestDeployment`, `latestDeploymentCount`, `latestDeploymentExists`. You can include multiple options by separating them with a comma.
     * @param  int  $pagesize  The number of results that will be returned per page.
     * @param  string  $pagecursor  The cursor to start the pagination from.
     */
    public function organizationsSitesIndex(
        string $organization,
        ?string $include,
        ?int $pagesize,
        ?string $pagecursor,
        ?string $filtername,
    ): Response {
        return $this->connector->send(new OrganizationsSitesIndex($organization, $include, $pagesize, $pagecursor, $filtername));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $site  The site ID
     */
    public function organizationsSitesShow(string $organization, int $site): Response
    {
        return $this->connector->send(new OrganizationsSitesShow($organization, $site));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  string  $sort  Available sorts are `name`, `created_at`, `updated_at`. You can sort by multiple options by separating them with a comma. To sort in descending order, use `-` sign in front of the sort, for example: `-name`.
     * @param  string  $include  Available includes are `tags`, `tagsCount`, `tagsExists`, `latestDeployment`, `latestDeploymentCount`, `latestDeploymentExists`. You can include multiple options by separating them with a comma.
     * @param  int  $pagesize  The number of results that will be returned per page.
     * @param  string  $pagecursor  The cursor to start the pagination from.
     * @param  string  $filtername  The name of the site.
     */
    public function organizationsServersSitesIndex(
        string $organization,
        int $server,
        ?string $sort,
        ?string $include,
        ?int $pagesize,
        ?string $pagecursor,
        ?string $filtername,
    ): Response {
        return $this->connector->send(new OrganizationsServersSitesIndex($organization, $server, $sort, $include, $pagesize, $pagecursor, $filtername));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     */
    public function organizationsServersSitesStore(string $organization, int $server): Response
    {
        return $this->connector->send(new OrganizationsServersSitesStore($organization, $server));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  int  $site  The site ID
     */
    public function organizationsServersSitesUpdate(string $organization, int $server, int $site): Response
    {
        return $this->connector->send(new OrganizationsServersSitesUpdate($organization, $server, $site));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  int  $site  The site ID
     */
    public function organizationsServersSitesDestroy(string $organization, int $server, int $site): Response
    {
        return $this->connector->send(new OrganizationsServersSitesDestroy($organization, $server, $site));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  int  $site  The site ID
     * @param  string  $sort  Available sorts are `name`, `created_at`. You can sort by multiple options by separating them with a comma. To sort in descending order, use `-` sign in front of the sort, for example: `-name`.
     * @param  int  $pagesize  The number of results that will be returned per page.
     * @param  string  $pagecursor  The cursor to start the pagination from.
     * @param  string  $filterstatus  The status of the domain.
     * @param  string  $filtertype  The type of domain.
     */
    public function organizationsServersSitesDomainsIndex(
        string $organization,
        int $server,
        int $site,
        ?string $sort,
        ?int $pagesize,
        ?string $pagecursor,
        ?string $filterstatus,
        ?string $filtertype,
    ): Response {
        return $this->connector->send(new OrganizationsServersSitesDomainsIndex($organization, $server, $site, $sort, $pagesize, $pagecursor, $filterstatus, $filtertype));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  int  $site  The site ID
     */
    public function organizationsServersSitesDomainsStore(string $organization, int $server, int $site): Response
    {
        return $this->connector->send(new OrganizationsServersSitesDomainsStore($organization, $server, $site));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  int  $site  The site ID
     * @param  int  $domainRecord  The domain record ID
     */
    public function organizationsServersSitesDomainsShow(
        string $organization,
        int $server,
        int $site,
        int $domainRecord,
    ): Response {
        return $this->connector->send(new OrganizationsServersSitesDomainsShow($organization, $server, $site, $domainRecord));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  int  $site  The site ID
     * @param  int  $domainRecord  The domain record ID
     */
    public function organizationsServersSitesDomainsDestroy(
        string $organization,
        int $server,
        int $site,
        int $domainRecord,
    ): Response {
        return $this->connector->send(new OrganizationsServersSitesDomainsDestroy($organization, $server, $site, $domainRecord));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  int  $site  The site ID
     * @param  int  $domainRecord  The domain record ID
     */
    public function organizationsServersSitesDomainsUpdate(
        string $organization,
        int $server,
        int $site,
        int $domainRecord,
    ): Response {
        return $this->connector->send(new OrganizationsServersSitesDomainsUpdate($organization, $server, $site, $domainRecord));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  int  $site  The site ID
     * @param  int  $domainRecord  The domain record ID
     */
    public function organizationsServersSitesDomainsConfigurations(
        string $organization,
        int $server,
        int $site,
        int $domainRecord,
    ): Response {
        return $this->connector->send(new OrganizationsServersSitesDomainsConfigurations($organization, $server, $site, $domainRecord));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  int  $site  The site ID
     * @param  int  $domainRecord  The domain record ID
     */
    public function organizationsServersSitesDomainsActionsStore(
        string $organization,
        int $server,
        int $site,
        int $domainRecord,
    ): Response {
        return $this->connector->send(new OrganizationsServersSitesDomainsActionsStore($organization, $server, $site, $domainRecord));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  int  $site  The site ID
     * @param  int  $domainRecord  The domain record ID
     */
    public function organizationsServersSitesDomainsCertificateShow(
        string $organization,
        int $server,
        int $site,
        int $domainRecord,
    ): Response {
        return $this->connector->send(new OrganizationsServersSitesDomainsCertificateShow($organization, $server, $site, $domainRecord));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  int  $site  The site ID
     * @param  int  $domainRecord  The domain record ID
     */
    public function organizationsServersSitesDomainsCertificateStore(
        string $organization,
        int $server,
        int $site,
        int $domainRecord,
    ): Response {
        return $this->connector->send(new OrganizationsServersSitesDomainsCertificateStore($organization, $server, $site, $domainRecord));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  int  $site  The site ID
     * @param  int  $domainRecord  The domain record ID
     */
    public function organizationsServersSitesDomainsCertificateDestroy(
        string $organization,
        int $server,
        int $site,
        int $domainRecord,
    ): Response {
        return $this->connector->send(new OrganizationsServersSitesDomainsCertificateDestroy($organization, $server, $site, $domainRecord));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  int  $site  The site ID
     */
    public function organizationsServersSitesHealthcheckShow(string $organization, int $server, int $site): Response
    {
        return $this->connector->send(new OrganizationsServersSitesHealthcheckShow($organization, $server, $site));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  int  $site  The site ID
     */
    public function organizationsServersSitesHealthcheckUpdate(string $organization, int $server, int $site): Response
    {
        return $this->connector->send(new OrganizationsServersSitesHealthcheckUpdate($organization, $server, $site));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  int  $site  The site ID
     */
    public function organizationsServersSitesEnvironmentShow(string $organization, int $server, int $site): Response
    {
        return $this->connector->send(new OrganizationsServersSitesEnvironmentShow($organization, $server, $site));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  int  $site  The site ID
     * @param  array  $body  The request body
     */
    public function organizationsServersSitesEnvironmentUpdate(string $organization, int $server, int $site, array $body): Response
    {
        $request = new OrganizationsServersSitesEnvironmentUpdate($organization, $server, $site);
        $request->body()->set($body);

        return $this->connector->send($request);
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  int  $site  The site ID
     */
    public function organizationsServersSitesNginxShow(string $organization, int $server, int $site): Response
    {
        return $this->connector->send(new OrganizationsServersSitesNginxShow($organization, $server, $site));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  int  $site  The site ID
     */
    public function organizationsServersSitesNginxUpdate(string $organization, int $server, int $site): Response
    {
        return $this->connector->send(new OrganizationsServersSitesNginxUpdate($organization, $server, $site));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  int  $site  The site ID
     */
    public function organizationsServersSitesComposerCredentialsIndex(
        string $organization,
        int $server,
        int $site,
    ): Response {
        return $this->connector->send(new OrganizationsServersSitesComposerCredentialsIndex($organization, $server, $site));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  int  $site  The site ID
     */
    public function organizationsServersSitesComposerCredentialsStore(
        string $organization,
        int $server,
        int $site,
    ): Response {
        return $this->connector->send(new OrganizationsServersSitesComposerCredentialsStore($organization, $server, $site));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  int  $site  The site ID
     */
    public function organizationsServersSitesComposerCredentialsShow(
        string $organization,
        int $server,
        int $site,
        string $repository,
    ): Response {
        return $this->connector->send(new OrganizationsServersSitesComposerCredentialsShow($organization, $server, $site, $repository));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  int  $site  The site ID
     */
    public function organizationsServersSitesComposerCredentialsUpdate(
        string $organization,
        int $server,
        int $site,
        string $repository,
    ): Response {
        return $this->connector->send(new OrganizationsServersSitesComposerCredentialsUpdate($organization, $server, $site, $repository));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  int  $site  The site ID
     */
    public function organizationsServersSitesComposerCredentialsDestroy(
        string $organization,
        int $server,
        int $site,
        string $repository,
    ): Response {
        return $this->connector->send(new OrganizationsServersSitesComposerCredentialsDestroy($organization, $server, $site, $repository));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  int  $site  The site ID
     * @param  string  $sort  Available sorts are `id`. You can sort by multiple options by separating them with a comma. To sort in descending order, use `-` sign in front of the sort, for example: `-id`.
     * @param  int  $pagesize  The number of results that will be returned per page.
     * @param  string  $pagecursor  The cursor to start the pagination from.
     */
    public function organizationsServersSitesLoadBalancingNodesIndex(
        string $organization,
        int $server,
        int $site,
        ?string $sort,
        ?int $pagesize,
        ?string $pagecursor,
    ): Response {
        return $this->connector->send(new OrganizationsServersSitesLoadBalancingNodesIndex($organization, $server, $site, $sort, $pagesize, $pagecursor));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  int  $site  The site ID
     */
    public function organizationsServersSitesLoadBalancingNodesUpdate(
        string $organization,
        int $server,
        int $site,
    ): Response {
        return $this->connector->send(new OrganizationsServersSitesLoadBalancingNodesUpdate($organization, $server, $site));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  int  $site  The site ID
     */
    public function organizationsServersSitesLogsNginxAccessShow(string $organization, int $server, int $site): Response
    {
        return $this->connector->send(new OrganizationsServersSitesLogsNginxAccessShow($organization, $server, $site));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  int  $site  The site ID
     */
    public function organizationsServersSitesLogsNginxAccessDestroy(
        string $organization,
        int $server,
        int $site,
    ): Response {
        return $this->connector->send(new OrganizationsServersSitesLogsNginxAccessDestroy($organization, $server, $site));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  int  $site  The site ID
     */
    public function organizationsServersSitesLogsNginxErrorShow(string $organization, int $server, int $site): Response
    {
        return $this->connector->send(new OrganizationsServersSitesLogsNginxErrorShow($organization, $server, $site));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  int  $site  The site ID
     */
    public function organizationsServersSitesLogsNginxErrorDestroy(
        string $organization,
        int $server,
        int $site,
    ): Response {
        return $this->connector->send(new OrganizationsServersSitesLogsNginxErrorDestroy($organization, $server, $site));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  int  $site  The site ID
     */
    public function organizationsServersSitesLogsApplicationShow(string $organization, int $server, int $site): Response
    {
        return $this->connector->send(new OrganizationsServersSitesLogsApplicationShow($organization, $server, $site));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  int  $site  The site ID
     */
    public function organizationsServersSitesLogsApplicationDestroy(
        string $organization,
        int $server,
        int $site,
    ): Response {
        return $this->connector->send(new OrganizationsServersSitesLogsApplicationDestroy($organization, $server, $site));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  int  $site  The site ID
     * @param  int  $pagesize  The number of results that will be returned per page.
     * @param  string  $pagecursor  The cursor to start the pagination from.
     */
    public function organizationsServersSitesHeartbeatsIndex(
        string $organization,
        int $server,
        int $site,
        ?int $pagesize,
        ?string $pagecursor,
    ): Response {
        return $this->connector->send(new OrganizationsServersSitesHeartbeatsIndex($organization, $server, $site, $pagesize, $pagecursor));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  int  $site  The site ID
     */
    public function organizationsServersSitesHeartbeatsStore(string $organization, int $server, int $site): Response
    {
        return $this->connector->send(new OrganizationsServersSitesHeartbeatsStore($organization, $server, $site));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  int  $site  The site ID
     * @param  int  $heartbeat  The heartbeat ID
     */
    public function organizationsServersSitesHeartbeatsShow(
        string $organization,
        int $server,
        int $site,
        int $heartbeat,
    ): Response {
        return $this->connector->send(new OrganizationsServersSitesHeartbeatsShow($organization, $server, $site, $heartbeat));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  int  $site  The site ID
     * @param  int  $heartbeat  The heartbeat ID
     */
    public function organizationsServersSitesHeartbeatsUpdate(
        string $organization,
        int $server,
        int $site,
        int $heartbeat,
    ): Response {
        return $this->connector->send(new OrganizationsServersSitesHeartbeatsUpdate($organization, $server, $site, $heartbeat));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  int  $site  The site ID
     * @param  int  $heartbeat  The heartbeat ID
     */
    public function organizationsServersSitesHeartbeatsDestroy(
        string $organization,
        int $server,
        int $site,
        int $heartbeat,
    ): Response {
        return $this->connector->send(new OrganizationsServersSitesHeartbeatsDestroy($organization, $server, $site, $heartbeat));
    }
}
