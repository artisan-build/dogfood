<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeSdk\Console\Commands;

use ArtisanBuild\ForgeSdk\Console\Concerns\HandlesDefaultArguments;
use ArtisanBuild\ForgeSdk\Console\Concerns\LogsForgeOperations;
use ArtisanBuild\ForgeSdk\Console\Concerns\ResolvesResourceIdentifiers;
use ArtisanBuild\ForgeSdk\ForgeSdk;
use Exception;
use Illuminate\Console\Command;
use InvalidArgumentException;

class GetDeploymentCommand extends Command
{
    use HandlesDefaultArguments;
    use LogsForgeOperations;
    use ResolvesResourceIdentifiers;

    protected $signature = 'forge:get-deployment
                            {organization? : The organization slug or ID}
                            {server? : The server name or ID}
                            {site? : The site name or ID}
                            {deployment? : The deployment ID}';

    protected $description = 'Get details for a specific deployment';

    public function handle(ForgeSdk $forge): int
    {
        $startTime = microtime(true);

        $organizationInput = $this->getOrganizationArgument();

        if (! $organizationInput) {
            $this->error('Organization is required. Either pass the organization argument or set FORGE_ORGANIZATION in your environment.');

            return self::FAILURE;
        }
        $serverInput = $this->getServerArgument();

        if (! $serverInput) {
            $this->error('Server is required. Either pass the server argument or set FORGE_SERVER in your environment.');

            return self::FAILURE;
        }
        $siteInput = $this->argument('site');
        $deploymentInput = $this->argument('deployment');

        try {
            $organization = $this->resolveOrganizationSlug($organizationInput, $forge);
            $server = $this->resolveServerIdentifier($serverInput, $organization, $forge);
            $site = $this->resolveSiteIdentifier($siteInput, $organization, $server, $forge);
            $deployment = is_numeric($deploymentInput) ? (int) $deploymentInput : $deploymentInput;
        } catch (InvalidArgumentException $e) {
            $this->error($e->getMessage());

            return self::FAILURE;
        }

        $this->logOperation('Get deployment', [
            'organization' => $organization,
            'server' => $server,
            'site' => $site,
            'deployment' => $deployment,
        ]);

        try {
            $response = $forge->deployments()->organizationsServersSitesDeploymentsShow($organization, $server, $site, $deployment);

            if (! $response->successful()) {
                $this->logError('Get deployment', $response->body(), [
                    'status' => $response->status(),
                    'organization' => $organization,
                    'server' => $server,
                    'site' => $site,
                    'deployment' => $deployment,
                ]);
                $this->error("Failed to get deployment: {$response->body()}");

                return self::FAILURE;
            }

            $data = $response->json();
            $deploymentData = $data['data'] ?? $data;

            $this->info('Deployment Details:');
            $this->line('');
            $this->line("ID: {$deploymentData['id']}");
            $this->line("Status: {$deploymentData['status']}");
            $this->line("Commit Hash: {$deploymentData['commit_hash']}");
            $this->line("Commit Author: {$deploymentData['commit_author']}");
            $this->line("Commit Message: {$deploymentData['commit_message']}");
            $this->line("Started: {$deploymentData['started_at']}");
            $this->line("Ended: {$deploymentData['ended_at']}");
            $this->line("Duration: {$deploymentData['duration']} seconds");
            $this->line("Created: {$deploymentData['created_at']}");

            $executionTime = round((microtime(true) - $startTime) * 1000, 2);

            $this->logSuccess('Get deployment', [
                'organization' => $organization,
                'server' => $server,
                'site' => $site,
                'deployment' => $deployment,
                'execution_time_ms' => $executionTime,
            ]);

            $this->newLine();
            $this->comment("Retrieved deployment details in {$executionTime}ms");

            return self::SUCCESS;
        } catch (Exception $e) {
            $executionTime = round((microtime(true) - $startTime) * 1000, 2);

            $this->logError('Get deployment', $e->getMessage(), [
                'organization' => $organization,
                'server' => $server,
                'site' => $site,
                'deployment' => $deployment,
                'execution_time_ms' => $executionTime,
            ]);

            $this->error("Error: {$e->getMessage()}");

            return self::FAILURE;
        }
    }
}
