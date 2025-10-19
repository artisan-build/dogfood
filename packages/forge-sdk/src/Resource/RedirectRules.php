<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeSdk\Resource;

use ArtisanBuild\ForgeSdk\Requests\RedirectRules\OrganizationsServersSitesRedirectRulesDestroy;
use ArtisanBuild\ForgeSdk\Requests\RedirectRules\OrganizationsServersSitesRedirectRulesIndex;
use ArtisanBuild\ForgeSdk\Requests\RedirectRules\OrganizationsServersSitesRedirectRulesShow;
use ArtisanBuild\ForgeSdk\Requests\RedirectRules\OrganizationsServersSitesRedirectRulesStore;
use ArtisanBuild\ForgeSdk\Resource;
use Saloon\Http\Response;

class RedirectRules extends Resource
{
    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  int  $site  The site ID
     * @param  string  $sort  Available sorts are `created_at`, `updated_at`. You can sort by multiple options by separating them with a comma. To sort in descending order, use `-` sign in front of the sort, for example: `-created_at`.
     * @param  int  $pagesize  The number of results that will be returned per page.
     * @param  string  $pagecursor  The cursor to start the pagination from.
     * @param  string  $filterfrom  The source URL path for the redirect rule.
     * @param  string  $filterto  The destination URL path for the redirect rule.
     * @param  string  $filtertype  The type of the redirect rule.
     * @param  string  $filterstatus  The status of the redirect rule.
     */
    public function organizationsServersSitesRedirectRulesIndex(
        string $organization,
        int $server,
        int $site,
        ?string $sort,
        ?int $pagesize,
        ?string $pagecursor,
        ?string $filterfrom,
        ?string $filterto,
        ?string $filtertype,
        ?string $filterstatus,
    ): Response {
        return $this->connector->send(new OrganizationsServersSitesRedirectRulesIndex($organization, $server, $site, $sort, $pagesize, $pagecursor, $filterfrom, $filterto, $filtertype, $filterstatus));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  int  $site  The site ID
     */
    public function organizationsServersSitesRedirectRulesStore(string $organization, int $server, int $site): Response
    {
        return $this->connector->send(new OrganizationsServersSitesRedirectRulesStore($organization, $server, $site));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  int  $site  The site ID
     * @param  int  $redirectRule  The redirect rule ID
     */
    public function organizationsServersSitesRedirectRulesShow(
        string $organization,
        int $server,
        int $site,
        int $redirectRule,
    ): Response {
        return $this->connector->send(new OrganizationsServersSitesRedirectRulesShow($organization, $server, $site, $redirectRule));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  int  $site  The site ID
     * @param  int  $redirectRule  The redirect rule ID
     */
    public function organizationsServersSitesRedirectRulesDestroy(
        string $organization,
        int $server,
        int $site,
        int $redirectRule,
    ): Response {
        return $this->connector->send(new OrganizationsServersSitesRedirectRulesDestroy($organization, $server, $site, $redirectRule));
    }
}
