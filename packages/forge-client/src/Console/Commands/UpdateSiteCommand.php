<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeClient\Console\Commands;

use ArtisanBuild\ForgeClient\Console\Concerns\HandlesDefaultArguments;
use ArtisanBuild\ForgeClient\Console\Concerns\LogsForgeOperations;
use ArtisanBuild\ForgeClient\Console\Concerns\PerformsDestructiveForgeOperations;
use ArtisanBuild\ForgeClient\Console\Concerns\ResolvesResourceIdentifiers;
use ArtisanBuild\ForgeClient\ForgeClient;
use ArtisanBuild\ForgeClient\Requests\Sites\OrganizationsServersSitesUpdate;
use Exception;
use Illuminate\Console\Command;
use InvalidArgumentException;

class UpdateSiteCommand extends Command
{
    use HandlesDefaultArguments;
    use LogsForgeOperations;
    use PerformsDestructiveForgeOperations;
    use ResolvesResourceIdentifiers;

    protected $signature = 'forge:update-site
                            {site? : The site name or ID}
                            {server? : The server name or ID}
                            {organization? : The organization slug or ID}
                            {--directory= : Web directory}
                            {--dangerously-skip-confirmation : Skip confirmation prompt}';

    protected $description = 'Update a site configuration';

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

        $directory = $this->option('directory');

        // Validate at least one option is provided
        if (! $directory) {
            $this->error('At least one option is required: --directory');

            return self::FAILURE;
        }

        $this->info('Updating site with the following changes:');
        $this->line("  Organization: {$organization}");
        $this->line("  Server: {$server}");
        $this->line("  Site: {$site}");

        if ($directory) {
            $this->line("  Directory: {$directory}");
        }

        $this->newLine();

        if (! $this->confirmOperation('Are you sure you want to update this site?')) {
            $this->info('Operation cancelled.');

            return self::SUCCESS;
        }

        $body = [];

        if ($directory) {
            $body['directory'] = $directory;
        }

        $this->logOperation('Update site', [
            'organization' => $organization,
            'server' => $server,
            'site' => $site,
        ], 'warning');

        try {
            $request = new OrganizationsServersSitesUpdate($organization, $server, $site);
            $request->body()->merge($body);

            $response = $forge->send($request);

            if (! $response->successful()) {
                $this->logError('Update site', $response->body(), [
                    'status' => $response->status(),
                    'organization' => $organization,
                    'server' => $server,
                    'site' => $site,
                ]);
                $this->error("Failed to update site: {$response->body()}");

                return self::FAILURE;
            }

            $data = $response->json();
            $siteData = $data['data'] ?? $data;

            $this->info('Site updated successfully!');
            $this->line("ID: {$siteData['id']}");
            $this->line("Name: {$siteData['name']}");
            $this->line("Directory: {$siteData['directory']}");

            $executionTime = round((microtime(true) - $startTime) * 1000, 2);

            $this->logSuccess('Update site', [
                'organization' => $organization,
                'server' => $server,
                'site' => $site,
                'execution_time_ms' => $executionTime,
            ]);

            $this->newLine();
            $this->comment("Site updated in {$executionTime}ms");

            return self::SUCCESS;
        } catch (Exception $e) {
            $executionTime = round((microtime(true) - $startTime) * 1000, 2);

            $this->logError('Update site', $e->getMessage(), [
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
