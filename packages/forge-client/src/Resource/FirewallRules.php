<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeClient\Resource;

use ArtisanBuild\ForgeClient\Requests\FirewallRules\OrganizationsServersFirewallRulesDestroy;
use ArtisanBuild\ForgeClient\Requests\FirewallRules\OrganizationsServersFirewallRulesIndex;
use ArtisanBuild\ForgeClient\Requests\FirewallRules\OrganizationsServersFirewallRulesShow;
use ArtisanBuild\ForgeClient\Requests\FirewallRules\OrganizationsServersFirewallRulesStore;
use ArtisanBuild\ForgeClient\Resource;
use Saloon\Http\Response;

class FirewallRules extends Resource
{
    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  string  $sort  Available sorts are `created_at`, `updated_at`. You can sort by multiple options by separating them with a comma. To sort in descending order, use `-` sign in front of the sort, for example: `-created_at`.
     * @param  int  $pagesize  The number of results that will be returned per page.
     * @param  string  $pagecursor  The cursor to start the pagination from.
     * @param  string  $filtername  The name of the firewall rule.
     * @param  string  $filterstatus  The status of the firewall rule.
     * @param  string  $filteripAddress  The IP address or subnet for the firewall rule.
     * @param  string  $filtertype  The type of the firewall rule.
     * @param  string  $filterport  The port or port range for the firewall rule.
     */
    public function organizationsServersFirewallRulesIndex(
        string $organization,
        int $server,
        ?string $sort,
        ?int $pagesize,
        ?string $pagecursor,
        ?string $filtername,
        ?string $filterstatus,
        ?string $filteripAddress,
        ?string $filtertype,
        ?string $filterport,
    ): Response {
        return $this->connector->send(new OrganizationsServersFirewallRulesIndex($organization, $server, $sort, $pagesize, $pagecursor, $filtername, $filterstatus, $filteripAddress, $filtertype, $filterport));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     */
    public function organizationsServersFirewallRulesStore(string $organization, int $server): Response
    {
        return $this->connector->send(new OrganizationsServersFirewallRulesStore($organization, $server));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  int  $rule  The rule ID
     */
    public function organizationsServersFirewallRulesShow(string $organization, int $server, int $rule): Response
    {
        return $this->connector->send(new OrganizationsServersFirewallRulesShow($organization, $server, $rule));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  int  $rule  The rule ID
     */
    public function organizationsServersFirewallRulesDestroy(string $organization, int $server, int $rule): Response
    {
        return $this->connector->send(new OrganizationsServersFirewallRulesDestroy($organization, $server, $rule));
    }
}
