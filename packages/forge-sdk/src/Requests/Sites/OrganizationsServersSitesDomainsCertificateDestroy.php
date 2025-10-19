<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeSdk\Requests\Sites;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * organizations.servers.sites.domains.certificate.destroy
 *
 * Delete the certificate for a given domain.
 *
 * Processing mode: <small><code>async</code></small>
 */
class OrganizationsServersSitesDomainsCertificateDestroy extends Request
{
    protected Method $method = Method::DELETE;

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
