<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeSdk\Console\Commands;

use ArtisanBuild\ForgeSdk\Console\Concerns\HandlesDefaultArguments;
use ArtisanBuild\ForgeSdk\Console\Concerns\LogsForgeOperations;
use ArtisanBuild\ForgeSdk\Console\Concerns\PerformsDestructiveForgeOperations;
use ArtisanBuild\ForgeSdk\Console\Concerns\ResolvesResourceIdentifiers;
use ArtisanBuild\ForgeSdk\ForgeSdk;
use ArtisanBuild\ForgeSdk\Requests\Sites\OrganizationsServersSitesStore;
use Exception;
use Illuminate\Console\Command;
use InvalidArgumentException;

class CreateSiteCommand extends Command
{
    use HandlesDefaultArguments;
    use LogsForgeOperations;
    use PerformsDestructiveForgeOperations;
    use ResolvesResourceIdentifiers;

    protected $signature = 'forge:create-site
                            {organization? : The organization slug or ID}
                            {server? : The server name or ID}
                            {--domain= : Site domain name}
                            {--project-type= : Project type (php, laravel, html, symfony, spa)}
                            {--directory= : Web directory (e.g., /public)}
                            {--php-version= : PHP version}
                            {--isolated= : Enable PHP-FPM isolation (true/false)}
                            {--username= : System username for isolated sites}
                            {--dangerously-skip-confirmation : Skip confirmation prompt}';

    protected $description = 'Create a new site on the server';

    public function handle(ForgeSdk $forge): int
    {
        $startTime = microtime(true);

        $organizationInput = $this->getOrganizationArgument();
        $serverInput = $this->getServerArgument();

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
        } catch (InvalidArgumentException $e) {
            $this->error($e->getMessage());

            return self::FAILURE;
        }

        $domain = $this->option('domain');
        $projectType = $this->option('project-type');
        $directory = $this->option('directory');
        $phpVersion = $this->option('php-version');
        $isolated = $this->option('isolated');
        $username = $this->option('username');

        // Validate required options
        if (! $domain || ! $projectType || ! $directory) {
            $this->error('Missing required options: --domain, --project-type, and --directory are required');

            return self::FAILURE;
        }

        $this->info('Creating site with the following configuration:');
        $this->line("  Organization: {$organization}");
        $this->line("  Server: {$server}");
        $this->line("  Domain: {$domain}");
        $this->line("  Project Type: {$projectType}");
        $this->line("  Directory: {$directory}");

        if ($phpVersion) {
            $this->line("  PHP Version: {$phpVersion}");
        }

        if ($isolated) {
            $this->line("  Isolated: {$isolated}");
        }

        if ($username) {
            $this->line("  Username: {$username}");
        }

        $this->newLine();

        if (! $this->confirmOperation('Are you sure you want to create this site?')) {
            $this->info('Operation cancelled.');

            return self::SUCCESS;
        }

        $body = [
            'domain' => $domain,
            'project_type' => $projectType,
            'directory' => $directory,
        ];

        if ($phpVersion) {
            $body['php_version'] = $phpVersion;
        }

        if ($isolated !== null) {
            $body['isolated'] = filter_var($isolated, FILTER_VALIDATE_BOOLEAN);
        }

        if ($username) {
            $body['username'] = $username;
        }

        $this->logOperation('Create site', [
            'organization' => $organization,
            'server' => $server,
            'domain' => $domain,
            'project_type' => $projectType,
        ], 'warning');

        try {
            $request = new OrganizationsServersSitesStore($organization, $server);
            $request->body()->merge($body);

            $response = $forge->send($request);

            if (! $response->successful()) {
                $this->logError('Create site', $response->body(), [
                    'status' => $response->status(),
                    'organization' => $organization,
                    'server' => $server,
                ]);
                $this->error("Failed to create site: {$response->body()}");

                return self::FAILURE;
            }

            $data = $response->json();
            $site = $data['data'] ?? $data;

            $this->info('Site created successfully!');
            $this->line("ID: {$site['id']}");
            $this->line("Name: {$site['name']}");
            $this->line("Status: {$site['status']}");

            $executionTime = round((microtime(true) - $startTime) * 1000, 2);

            $this->logSuccess('Create site', [
                'organization' => $organization,
                'server' => $server,
                'site_id' => $site['id'],
                'site_name' => $site['name'],
                'execution_time_ms' => $executionTime,
            ]);

            $this->newLine();
            $this->comment("Site created in {$executionTime}ms");

            return self::SUCCESS;
        } catch (Exception $e) {
            $executionTime = round((microtime(true) - $startTime) * 1000, 2);

            $this->logError('Create site', $e->getMessage(), [
                'organization' => $organization,
                'server' => $server,
                'execution_time_ms' => $executionTime,
            ]);

            $this->error("Error: {$e->getMessage()}");

            return self::FAILURE;
        }
    }
}
