<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeSdk\Resource;

use ArtisanBuild\ForgeSdk\Requests\Integrations\OrganizationsServersSitesIntegrationsHorizonDestroy;
use ArtisanBuild\ForgeSdk\Requests\Integrations\OrganizationsServersSitesIntegrationsHorizonShow;
use ArtisanBuild\ForgeSdk\Requests\Integrations\OrganizationsServersSitesIntegrationsHorizonStore;
use ArtisanBuild\ForgeSdk\Requests\Integrations\OrganizationsServersSitesIntegrationsInertiaShow;
use ArtisanBuild\ForgeSdk\Requests\Integrations\OrganizationsServersSitesIntegrationsInertiaStore;
use ArtisanBuild\ForgeSdk\Requests\Integrations\OrganizationsServersSitesIntegrationsLaravelMaintenanceDestroy;
use ArtisanBuild\ForgeSdk\Requests\Integrations\OrganizationsServersSitesIntegrationsLaravelMaintenanceShow;
use ArtisanBuild\ForgeSdk\Requests\Integrations\OrganizationsServersSitesIntegrationsLaravelMaintenanceStore;
use ArtisanBuild\ForgeSdk\Requests\Integrations\OrganizationsServersSitesIntegrationsLaravelSchedulerDestroy;
use ArtisanBuild\ForgeSdk\Requests\Integrations\OrganizationsServersSitesIntegrationsLaravelSchedulerShow;
use ArtisanBuild\ForgeSdk\Requests\Integrations\OrganizationsServersSitesIntegrationsLaravelSchedulerStore;
use ArtisanBuild\ForgeSdk\Requests\Integrations\OrganizationsServersSitesIntegrationsOctaneDestroy;
use ArtisanBuild\ForgeSdk\Requests\Integrations\OrganizationsServersSitesIntegrationsOctaneShow;
use ArtisanBuild\ForgeSdk\Requests\Integrations\OrganizationsServersSitesIntegrationsOctaneStore;
use ArtisanBuild\ForgeSdk\Requests\Integrations\OrganizationsServersSitesIntegrationsPulseDestroy;
use ArtisanBuild\ForgeSdk\Requests\Integrations\OrganizationsServersSitesIntegrationsPulseShow;
use ArtisanBuild\ForgeSdk\Requests\Integrations\OrganizationsServersSitesIntegrationsPulseStore;
use ArtisanBuild\ForgeSdk\Requests\Integrations\OrganizationsServersSitesIntegrationsReverbDestroy;
use ArtisanBuild\ForgeSdk\Requests\Integrations\OrganizationsServersSitesIntegrationsReverbShow;
use ArtisanBuild\ForgeSdk\Requests\Integrations\OrganizationsServersSitesIntegrationsReverbStore;
use ArtisanBuild\ForgeSdk\Resource;
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
