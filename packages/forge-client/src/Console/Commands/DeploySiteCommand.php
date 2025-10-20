<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeClient\Console\Commands;

use ArtisanBuild\ForgeClient\Console\Concerns\HandlesDefaultArguments;
use ArtisanBuild\ForgeClient\Console\Concerns\LogsForgeOperations;
use ArtisanBuild\ForgeClient\Console\Concerns\PerformsDestructiveForgeOperations;
use ArtisanBuild\ForgeClient\Console\Concerns\ResolvesResourceIdentifiers;
use ArtisanBuild\ForgeClient\ForgeClient;
use ArtisanBuild\ForgeClient\Requests\Deployments\OrganizationsServersSitesDeploymentsStore;
use Exception;
use Illuminate\Console\Command;
use InvalidArgumentException;

class DeploySiteCommand extends Command
{
    use HandlesDefaultArguments;
    use LogsForgeOperations;
    use PerformsDestructiveForgeOperations;
    use ResolvesResourceIdentifiers;

    protected $signature = 'forge:deploy-site
                            {site? : The site name or ID}
                            {server? : The server name or ID}
                            {organization? : The organization slug or ID}
                            {--dangerously-skip-confirmation : Skip confirmation prompt}';

    protected $description = 'Trigger a deployment for a site';

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

        try {
            $organization = $this->resolveOrganizationSlug($organizationInput, $forge);
            $server = $this->resolveServerIdentifier($serverInput, $organization, $forge);
            $site = $this->resolveSiteIdentifier($siteInput, $organization, $server, $forge);
        } catch (InvalidArgumentException $e) {
            $this->error($e->getMessage());

            return self::FAILURE;
        }

        $this->info('Deploying site:');
        $this->line("  Organization: {$organization}");
        $this->line("  Server: {$server}");
        $this->line("  Site: {$site}");
        $this->newLine();

        if (! $this->confirmOperation('Are you sure you want to deploy this site?')) {
            $this->info('Operation cancelled.');

            return self::SUCCESS;
        }

        $this->logOperation('Deploy site', [
            'organization' => $organization,
            'server' => $server,
            'site' => $site,
        ], 'warning');

        try {
            $request = new OrganizationsServersSitesDeploymentsStore($organization, $server, $site);

            $response = $forge->send($request);

            if (! $response->successful()) {
                $this->logError('Deploy site', $response->body(), [
                    'status' => $response->status(),
                    'organization' => $organization,
                    'server' => $server,
                    'site' => $site,
                ]);
                $this->error("Failed to trigger deployment: {$response->body()}");

                return self::FAILURE;
            }

            $data = $response->json();
            $deployment = $data['data'] ?? $data;

            $this->info('Deployment triggered successfully!');
            $this->line("ID: {$deployment['id']}");
            $this->line("Status: {$deployment['status']}");

            $executionTime = round((microtime(true) - $startTime) * 1000, 2);

            $this->logSuccess('Deploy site', [
                'organization' => $organization,
                'server' => $server,
                'site' => $site,
                'deployment_id' => $deployment['id'],
                'execution_time_ms' => $executionTime,
            ]);

            $this->newLine();
            $this->comment("Deployment triggered in {$executionTime}ms");

            return self::SUCCESS;
        } catch (Exception $e) {
            $executionTime = round((microtime(true) - $startTime) * 1000, 2);

            $this->logError('Deploy site', $e->getMessage(), [
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
