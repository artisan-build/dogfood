<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeClient\Requests\Servers;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * organizations.servers.events.show
 *
 * Get a specific event associated with the server.
 *
 * Processing mode: <small><code>sync</code></small>
 */
class OrganizationsServersEventsShow extends Request
{
    protected Method $method = Method::GET;

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  int  $event  The event ID
     */
    public function __construct(
        protected string $organization,
        protected int $server,
        protected int $event,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/orgs/{$this->organization}/servers/{$this->server}/events/{$this->event}";
    }
}
