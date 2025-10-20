<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeSdk\Resource;

use ArtisanBuild\ForgeSdk\Requests\SecurityRules\OrganizationsServersSitesSecurityRulesDestroy;
use ArtisanBuild\ForgeSdk\Requests\SecurityRules\OrganizationsServersSitesSecurityRulesIndex;
use ArtisanBuild\ForgeSdk\Requests\SecurityRules\OrganizationsServersSitesSecurityRulesShow;
use ArtisanBuild\ForgeSdk\Requests\SecurityRules\OrganizationsServersSitesSecurityRulesStore;
use ArtisanBuild\ForgeSdk\Requests\SecurityRules\OrganizationsServersSitesSecurityRulesUpdate;
use ArtisanBuild\ForgeSdk\Resource;
use Saloon\Http\Response;

class SecurityRules extends Resource
{
    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  int  $site  The site ID
     * @param  string  $sort  Available sorts are `path`, `status`, `created_at`, `updated_at`. You can sort by multiple options by separating them with a comma. To sort in descending order, use `-` sign in front of the sort, for example: `-path`.
     * @param  int  $pagesize  The number of results that will be returned per page.
     * @param  string  $pagecursor  The cursor to start the pagination from.
     * @param  string  $filterpath  The path for the security rule.
     * @param  string  $filterstatus  The status of the security rule.
     */
    public function organizationsServersSitesSecurityRulesIndex(
        string $organization,
        int $server,
        int $site,
        ?string $sort,
        ?int $pagesize,
        ?string $pagecursor,
        ?string $filterpath,
        ?string $filterstatus,
    ): Response {
        return $this->connector->send(new OrganizationsServersSitesSecurityRulesIndex($organization, $server, $site, $sort, $pagesize, $pagecursor, $filterpath, $filterstatus));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  int  $site  The site ID
     */
    public function organizationsServersSitesSecurityRulesStore(string $organization, int $server, int $site): Response
    {
        return $this->connector->send(new OrganizationsServersSitesSecurityRulesStore($organization, $server, $site));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  int  $site  The site ID
     * @param  int  $securityRule  The security rule ID
     */
    public function organizationsServersSitesSecurityRulesShow(
        string $organization,
        int $server,
        int $site,
        int $securityRule,
    ): Response {
        return $this->connector->send(new OrganizationsServersSitesSecurityRulesShow($organization, $server, $site, $securityRule));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  int  $site  The site ID
     * @param  int  $securityRule  The security rule ID
     */
    public function organizationsServersSitesSecurityRulesUpdate(
        string $organization,
        int $server,
        int $site,
        int $securityRule,
    ): Response {
        return $this->connector->send(new OrganizationsServersSitesSecurityRulesUpdate($organization, $server, $site, $securityRule));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  int  $site  The site ID
     * @param  int  $securityRule  The security rule ID
     */
    public function organizationsServersSitesSecurityRulesDestroy(
        string $organization,
        int $server,
        int $site,
        int $securityRule,
    ): Response {
        return $this->connector->send(new OrganizationsServersSitesSecurityRulesDestroy($organization, $server, $site, $securityRule));
    }
}
