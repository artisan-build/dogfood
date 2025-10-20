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

class GetSiteCommand extends Command
{
    use HandlesDefaultArguments;
    use LogsForgeOperations;
    use ResolvesResourceIdentifiers;

    protected $signature = 'forge:get-site
                            {site? : The site name or ID}
                            {server? : The server name or ID}
                            {organization? : The organization slug or ID}';

    protected $description = 'Get details for a specific site';

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

        try {
            $organization = $this->resolveOrganizationSlug($organizationInput, $forge);
            $server = $this->resolveServerIdentifier($serverInput, $organization, $forge);
            $site = $this->resolveSiteIdentifier($siteInput, $organization, $server, $forge);
        } catch (InvalidArgumentException $e) {
            $this->error($e->getMessage());

            return self::FAILURE;
        }

        $this->logOperation('Get site', [
            'organization' => $organization,
            'server' => $server,
            'site' => $site,
        ]);

        try {
            $response = $forge->sites()->organizationsSitesShow($organization, $site);

            if (! $response->successful()) {
                $this->logError('Get site', $response->body(), [
                    'status' => $response->status(),
                    'organization' => $organization,
                    'server' => $server,
                    'site' => $site,
                ]);
                $this->error("Failed to get site: {$response->body()}");

                return self::FAILURE;
            }

            $data = $response->json();
            $siteData = $data['data'] ?? $data;

            $this->info('Site Details:');
            $this->line('');
            $this->line("ID: {$siteData['id']}");
            $this->line("Name: {$siteData['name']}");
            $this->line("Directory: {$siteData['directory']}");
            $this->line("Status: {$siteData['status']}");
            $this->line("Project Type: {$siteData['project_type']}");
            $this->line("PHP Version: {$siteData['php_version']}");

            if (isset($siteData['repository'])) {
                $this->line("Repository: {$siteData['repository']}");
                $this->line("Branch: {$siteData['repository_branch']}");
                $this->line("Repository Status: {$siteData['repository_status']}");
            }

            $this->line('Quick Deploy: '.($siteData['quick_deploy'] ? 'Enabled' : 'Disabled'));
            $this->line("Deployment Status: {$siteData['deployment_status']}");
            $this->line("Created: {$siteData['created_at']}");

            $executionTime = round((microtime(true) - $startTime) * 1000, 2);

            $this->logSuccess('Get site', [
                'organization' => $organization,
                'server' => $server,
                'site' => $site,
                'execution_time_ms' => $executionTime,
            ]);

            $this->newLine();
            $this->comment("Retrieved site details in {$executionTime}ms");

            return self::SUCCESS;
        } catch (Exception $e) {
            $executionTime = round((microtime(true) - $startTime) * 1000, 2);

            $this->logError('Get site', $e->getMessage(), [
                'organization' => $organization,
                'server' => $server,
                'site' => $site,
                'execution_time_ms' => $executionTime,
            ]);

            $this->error("Error: {$e->getMessage()}");

            return self::FAILURE;
        }
    }
}
