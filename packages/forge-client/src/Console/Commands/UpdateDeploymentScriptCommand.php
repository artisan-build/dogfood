<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeClient\Console\Commands;

use ArtisanBuild\ForgeClient\Console\Concerns\HandlesDefaultArguments;
use ArtisanBuild\ForgeClient\Console\Concerns\LogsForgeOperations;
use ArtisanBuild\ForgeClient\Console\Concerns\PerformsDestructiveForgeOperations;
use ArtisanBuild\ForgeClient\Console\Concerns\ResolvesResourceIdentifiers;
use ArtisanBuild\ForgeClient\ForgeClient;
use ArtisanBuild\ForgeClient\Requests\Deployments\OrganizationsServersSitesDeploymentsScriptUpdate;
use Exception;
use Illuminate\Console\Command;
use InvalidArgumentException;

class UpdateDeploymentScriptCommand extends Command
{
    use HandlesDefaultArguments;
    use LogsForgeOperations;
    use PerformsDestructiveForgeOperations;
    use ResolvesResourceIdentifiers;

    protected $signature = 'forge:update-deployment-script
                            {site? : The site name or ID}
                            {server? : The server name or ID}
                            {organization? : The organization slug or ID}
                            {--script= : The deployment script content}
                            {--dangerously-skip-confirmation : Skip confirmation prompt}';

    protected $description = 'Update the deployment script for a site';

    public function handle(ForgeClient $forge): int
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

        $script = $this->option('script');

        if (! $script) {
            $this->error('The --script option is required');

            return self::FAILURE;
        }

        $this->info('Updating deployment script for site:');
        $this->line("  Organization: {$organization}");
        $this->line("  Server: {$server}");
        $this->line("  Site: {$site}");
        $this->newLine();
        $this->line('New script:');
        $this->line($script);
        $this->newLine();

        if (! $this->confirmOperation('Are you sure you want to update the deployment script?')) {
            $this->info('Operation cancelled.');

            return self::SUCCESS;
        }

        $this->logOperation('Update deployment script', [
            'organization' => $organization,
            'server' => $server,
            'site' => $site,
        ], 'warning');

        try {
            $request = new OrganizationsServersSitesDeploymentsScriptUpdate($organization, $server, $site);
            $request->body()->merge([
                'content' => $script,
            ]);

            $response = $forge->send($request);

            if (! $response->successful()) {
                $this->logError('Update deployment script', $response->body(), [
                    'status' => $response->status(),
                    'organization' => $organization,
                    'server' => $server,
                    'site' => $site,
                ]);
                $this->error("Failed to update deployment script: {$response->body()}");

                return self::FAILURE;
            }

            $data = $response->json();
            $siteData = $data['data'] ?? $data;

            $this->info('Deployment script updated successfully!');
            $this->line("ID: {$siteData['id']}");

            $executionTime = round((microtime(true) - $startTime) * 1000, 2);

            $this->logSuccess('Update deployment script', [
                'organization' => $organization,
                'server' => $server,
                'site' => $site,
                'execution_time_ms' => $executionTime,
            ]);

            $this->newLine();
            $this->comment("Deployment script updated in {$executionTime}ms");

            return self::SUCCESS;
        } catch (Exception $e) {
            $executionTime = round((microtime(true) - $startTime) * 1000, 2);

            $this->logError('Update deployment script', $e->getMessage(), [
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
