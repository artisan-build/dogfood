<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeClient\Requests\Sites;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * organizations.servers.sites.domains.certificate.store
 *
 * Create a new certificate for a given domain.
 *
 * Processing mode: <small><code>async</code></small>
 */
class OrganizationsServersSitesDomainsCertificateStore extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  int  $site  The site ID
     * @param  int  $domainRecord  The domain record ID
     */
    public function __construct(
        protected string $organization,
        protected int $server,
        protected int $site,
        protected int $domainRecord,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/orgs/{$this->organization}/servers/{$this->server}/sites/{$this->site}/domains/{$this->domainRecord}/certificate";
    }
}
