<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeClient\Console\Commands;

use ArtisanBuild\ForgeClient\Console\Concerns\HandlesDefaultArguments;
use ArtisanBuild\ForgeClient\Console\Concerns\LogsForgeOperations;
use ArtisanBuild\ForgeClient\Console\Concerns\ResolvesResourceIdentifiers;
use ArtisanBuild\ForgeClient\ForgeClient;
use Exception;
use Illuminate\Console\Command;
use InvalidArgumentException;

class GetEnvironmentCommand extends Command
{
    use HandlesDefaultArguments;
    use LogsForgeOperations;
    use ResolvesResourceIdentifiers;

    protected $signature = 'forge:get-environment
                            {site? : The site name or ID}
                            {server? : The server name or ID}
                            {organization? : The organization slug or ID}';

    protected $description = 'Get the environment file (.env) content for a site';

    public function handle(ForgeClient $forge): int
    {
        $startTime = microtime(true);

        $organizationInput = $this->getOrganizationArgument();
        $serverInput = $this->getServerArgument();
        $siteInput = $this->argument('site');

        if (! $organizationInput) {
            $this->error('Organization is required. Either pass the organization argument or set FORGE_ORGANIZATION in your environment.');

            return self::FAILURE;
        }

        if (! $serverInput) {
            $this->error('Server is required. Either pass the server argument or set FORGE_SERVER in your environment.');

            return self::FAILURE;
        }

        if (! $siteInput) {
            $this->error('Site is required.');

            return self::FAILURE;
        }

        try {
            $organization = $this->resolveOrganizationSlug($organizationInput, $forge);
            $serverId = $this->resolveServerId($serverInput, $organization, $forge);
            $siteId = $this->resolveSiteIdentifier($siteInput, $organization, $serverId, $forge);
        } catch (InvalidArgumentException $e) {
            $this->error($e->getMessage());

            return self::FAILURE;
        }

        $this->logOperation('Get environment', [
            'organization' => $organization,
            'server_id' => $serverId,
            'site_id' => $siteId,
        ]);

        try {
            $response = $forge->sites()->organizationsServersSitesEnvironmentShow(
                organization: $organization,
                server: $serverId,
                site: $siteId
            );

            if (! $response->successful()) {
                $this->logError('Get environment', $response->body(), [
                    'status' => $response->status(),
                    'organization' => $organization,
                    'server_id' => $serverId,
                    'site_id' => $siteId,
                ]);
                $this->error("Failed to get environment: {$response->body()}");

                return self::FAILURE;
            }

            $data = $response->json();
            $environment = $data['environment'] ?? '';

            $executionTime = round((microtime(true) - $startTime) * 1000, 2);

            $this->logSuccess('Get environment', [
                'organization' => $organization,
                'server_id' => $serverId,
                'site_id' => $siteId,
                'execution_time_ms' => $executionTime,
            ]);

            // Output the environment content
            $this->line($environment);

            return self::SUCCESS;
        } catch (Exception $e) {
            $executionTime = round((microtime(true) - $startTime) * 1000, 2);

            $this->logError('Get environment', $e->getMessage(), [
                'organization' => $organization,
                'server_id' => $serverId,
                'site_id' => $siteId,
                'execution_time_ms' => $executionTime,
            ]);

            $this->error("Error: {$e->getMessage()}");

            return self::FAILURE;
        }
    }
}
