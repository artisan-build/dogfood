<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeClient\Resource;

use ArtisanBuild\ForgeClient\Requests\Integrations\OrganizationsServersSitesIntegrationsHorizonDestroy;
use ArtisanBuild\ForgeClient\Requests\Integrations\OrganizationsServersSitesIntegrationsHorizonShow;
use ArtisanBuild\ForgeClient\Requests\Integrations\OrganizationsServersSitesIntegrationsHorizonStore;
use ArtisanBuild\ForgeClient\Requests\Integrations\OrganizationsServersSitesIntegrationsInertiaShow;
use ArtisanBuild\ForgeClient\Requests\Integrations\OrganizationsServersSitesIntegrationsInertiaStore;
use ArtisanBuild\ForgeClient\Requests\Integrations\OrganizationsServersSitesIntegrationsLaravelMaintenanceDestroy;
use ArtisanBuild\ForgeClient\Requests\Integrations\OrganizationsServersSitesIntegrationsLaravelMaintenanceShow;
use ArtisanBuild\ForgeClient\Requests\Integrations\OrganizationsServersSitesIntegrationsLaravelMaintenanceStore;
use ArtisanBuild\ForgeClient\Requests\Integrations\OrganizationsServersSitesIntegrationsLaravelSchedulerDestroy;
use ArtisanBuild\ForgeClient\Requests\Integrations\OrganizationsServersSitesIntegrationsLaravelSchedulerShow;
use ArtisanBuild\ForgeClient\Requests\Integrations\OrganizationsServersSitesIntegrationsLaravelSchedulerStore;
use ArtisanBuild\ForgeClient\Requests\Integrations\OrganizationsServersSitesIntegrationsOctaneDestroy;
use ArtisanBuild\ForgeClient\Requests\Integrations\OrganizationsServersSitesIntegrationsOctaneShow;
use ArtisanBuild\ForgeClient\Requests\Integrations\OrganizationsServersSitesIntegrationsOctaneStore;
use ArtisanBuild\ForgeClient\Requests\Integrations\OrganizationsServersSitesIntegrationsPulseDestroy;
use ArtisanBuild\ForgeClient\Requests\Integrations\OrganizationsServersSitesIntegrationsPulseShow;
use ArtisanBuild\ForgeClient\Requests\Integrations\OrganizationsServersSitesIntegrationsPulseStore;
use ArtisanBuild\ForgeClient\Requests\Integrations\OrganizationsServersSitesIntegrationsReverbDestroy;
use ArtisanBuild\ForgeClient\Requests\Integrations\OrganizationsServersSitesIntegrationsReverbShow;
use ArtisanBuild\ForgeClient\Requests\Integrations\OrganizationsServersSitesIntegrationsReverbStore;
use ArtisanBuild\ForgeClient\Resource;
use Saloon\Http\Response;

class Integrations extends Resource
{
    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  int  $site  The site ID
     */
    public function organizationsServersSitesIntegrationsHorizonShow(
        string $organization,
        int $server,
        int $site,
    ): Response {
        return $this->connector->send(new OrganizationsServersSitesIntegrationsHorizonShow($organization, $server, $site));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  int  $site  The site ID
     */
    public function organizationsServersSitesIntegrationsHorizonStore(
        string $organization,
        int $server,
        int $site,
    ): Response {
        return $this->connector->send(new OrganizationsServersSitesIntegrationsHorizonStore($organization, $server, $site));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  int  $site  The site ID
     */
    public function organizationsServersSitesIntegrationsHorizonDestroy(
        string $organization,
        int $server,
        int $site,
    ): Response {
        return $this->connector->send(new OrganizationsServersSitesIntegrationsHorizonDestroy($organization, $server, $site));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  int  $site  The site ID
     */
    public function organizationsServersSitesIntegrationsOctaneShow(
        string $organization,
        int $server,
        int $site,
    ): Response {
        return $this->connector->send(new OrganizationsServersSitesIntegrationsOctaneShow($organization, $server, $site));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  int  $site  The site ID
     */
    public function organizationsServersSitesIntegrationsOctaneStore(
        string $organization,
        int $server,
        int $site,
    ): Response {
        return $this->connector->send(new OrganizationsServersSitesIntegrationsOctaneStore($organization, $server, $site));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  int  $site  The site ID
     */
    public function organizationsServersSitesIntegrationsOctaneDestroy(
        string $organization,
        int $server,
        int $site,
    ): Response {
        return $this->connector->send(new OrganizationsServersSitesIntegrationsOctaneDestroy($organization, $server, $site));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  int  $site  The site ID
     */
    public function organizationsServersSitesIntegrationsReverbShow(
        string $organization,
        int $server,
        int $site,
    ): Response {
        return $this->connector->send(new OrganizationsServersSitesIntegrationsReverbShow($organization, $server, $site));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  int  $site  The site ID
     */
    public function organizationsServersSitesIntegrationsReverbStore(
        string $organization,
        int $server,
        int $site,
    ): Response {
        return $this->connector->send(new OrganizationsServersSitesIntegrationsReverbStore($organization, $server, $site));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  int  $site  The site ID
     */
    public function organizationsServersSitesIntegrationsReverbDestroy(
        string $organization,
        int $server,
        int $site,
    ): Response {
        return $this->connector->send(new OrganizationsServersSitesIntegrationsReverbDestroy($organization, $server, $site));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  int  $site  The site ID
     */
    public function organizationsServersSitesIntegrationsInertiaShow(
        string $organization,
        int $server,
        int $site,
    ): Response {
        return $this->connector->send(new OrganizationsServersSitesIntegrationsInertiaShow($organization, $server, $site));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  int  $site  The site ID
     */
    public function organizationsServersSitesIntegrationsInertiaStore(
        string $organization,
        int $server,
        int $site,
    ): Response {
        return $this->connector->send(new OrganizationsServersSitesIntegrationsInertiaStore($organization, $server, $site));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  int  $site  The site ID
     */
    public function organizationsServersSitesIntegrationsPulseShow(
        string $organization,
        int $server,
        int $site,
    ): Response {
        return $this->connector->send(new OrganizationsServersSitesIntegrationsPulseShow($organization, $server, $site));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  int  $site  The site ID
     */
    public function organizationsServersSitesIntegrationsPulseStore(
        string $organization,
        int $server,
        int $site,
    ): Response {
        return $this->connector->send(new OrganizationsServersSitesIntegrationsPulseStore($organization, $server, $site));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  int  $site  The site ID
     */
    public function organizationsServersSitesIntegrationsPulseDestroy(
        string $organization,
        int $server,
        int $site,
    ): Response {
        return $this->connector->send(new OrganizationsServersSitesIntegrationsPulseDestroy($organization, $server, $site));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  int  $site  The site ID
     */
    public function organizationsServersSitesIntegrationsLaravelMaintenanceShow(
        string $organization,
        int $server,
        int $site,
    ): Response {
        return $this->connector->send(new OrganizationsServersSitesIntegrationsLaravelMaintenanceShow($organization, $server, $site));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  int  $site  The site ID
     */
    public function organizationsServersSitesIntegrationsLaravelMaintenanceStore(
        string $organization,
        int $server,
        int $site,
    ): Response {
        return $this->connector->send(new OrganizationsServersSitesIntegrationsLaravelMaintenanceStore($organization, $server, $site));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  int  $site  The site ID
     */
    public function organizationsServersSitesIntegrationsLaravelMaintenanceDestroy(
        string $organization,
        int $server,
        int $site,
    ): Response {
        return $this->connector->send(new OrganizationsServersSitesIntegrationsLaravelMaintenanceDestroy($organization, $server, $site));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  int  $site  The site ID
     */
    public function organizationsServersSitesIntegrationsLaravelSchedulerShow(
        string $organization,
        int $server,
        int $site,
    ): Response {
        return $this->connector->send(new OrganizationsServersSitesIntegrationsLaravelSchedulerShow($organization, $server, $site));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  int  $site  The site ID
     */
    public function organizationsServersSitesIntegrationsLaravelSchedulerStore(
        string $organization,
        int $server,
        int $site,
    ): Response {
        return $this->connector->send(new OrganizationsServersSitesIntegrationsLaravelSchedulerStore($organization, $server, $site));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  int  $site  The site ID
     */
    public function organizationsServersSitesIntegrationsLaravelSchedulerDestroy(
        string $organization,
        int $server,
        int $site,
    ): Response {
        return $this->connector->send(new OrganizationsServersSitesIntegrationsLaravelSchedulerDestroy($organization, $server, $site));
    }
}
