<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeClient\Requests\ScheduledJobs;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * organizations.servers.scheduled-jobs.outputs.show
 *
 * Show a specific scheduled job output.
 *
 * Processing mode: <small><code>sync</code></small>
 */
class OrganizationsServersScheduledJobsOutputsShow extends Request
{
    protected Method $method = Method::GET;

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  int  $job  The job ID
     */
    public function __construct(
        protected string $organization,
        protected int $server,
        protected int $job,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/orgs/{$this->organization}/servers/{$this->server}/scheduled-jobs/{$this->job}/output";
    }
}
