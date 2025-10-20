<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeSdk\Console\Concerns;

trait HandlesDefaultArguments
{
    /**
     * Get the organization argument or default from config
     */
    protected function getOrganizationArgument(): ?string
    {
        $organization = $this->argument('organization');

        if ($organization) {
            return (string) $organization;
        }

        return config('forge-sdk.default_organization');
    }

    /**
     * Get the server argument or default from config
     */
    protected function getServerArgument(): ?string
    {
        $server = $this->argument('server');

        if ($server) {
            return (string) $server;
        }

        return config('forge-sdk.default_server');
    }

    /**
     * Validate that organization is provided or configured
     */
    protected function requireOrganization(): bool
    {
        if (! $this->getOrganizationArgument()) {
            $this->error('Organization is required. Either pass the organization argument or set FORGE_ORGANIZATION in your environment.');

            return false;
        }

        return true;
    }

    /**
     * Validate that server is provided or configured
     */
    protected function requireServer(): bool
    {
        if (! $this->getServerArgument()) {
            $this->error('Server is required. Either pass the server argument or set FORGE_SERVER in your environment.');

            return false;
        }

        return true;
    }
}
