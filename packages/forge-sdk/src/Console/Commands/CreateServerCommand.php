<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeSdk\Console\Commands;

use ArtisanBuild\ForgeSdk\Console\Concerns\HandlesDefaultArguments;
use ArtisanBuild\ForgeSdk\Console\Concerns\LogsForgeOperations;
use ArtisanBuild\ForgeSdk\Console\Concerns\PerformsDestructiveForgeOperations;
use ArtisanBuild\ForgeSdk\Console\Concerns\ResolvesResourceIdentifiers;
use ArtisanBuild\ForgeSdk\ForgeSdk;
use ArtisanBuild\ForgeSdk\Requests\Servers\OrganizationsServersStore;
use Exception;
use Illuminate\Console\Command;
use InvalidArgumentException;

class CreateServerCommand extends Command
{
    use HandlesDefaultArguments;
    use LogsForgeOperations;
    use PerformsDestructiveForgeOperations;
    use ResolvesResourceIdentifiers;

    protected $signature = 'forge:create-server
                            {organization? : The organization slug or ID}
                            {--name= : Server name}
                            {--provider= : Provider (laravel, ocean2, hetzner, vultr, akamai, aws, custom)}
                            {--credential= : Server credential ID}
                            {--region= : Server region (required - use forge:list-provider-regions to find codes)}
                            {--size= : Server size ID}
                            {--type=app : Server type (app, web, database, cache, worker, meilisearch, scheduler, loadbalancer)}
                            {--ubuntu-version=24.04 : Ubuntu version (22.04 or 24.04)}
                            {--php-version= : PHP version (php81, php82, php83, php84) - defaults to FORGE_PHP_VERSION}
                            {--database= : Database type (mysql8, postgres, mariadb, none) - defaults to FORGE_DATABASE}
                            {--dangerously-skip-confirmation : Skip confirmation prompt}';

    protected $description = 'Create a new server in the organization';

    public function handle(ForgeSdk $forge): int
    {
        $startTime = microtime(true);

        $organizationInput = $this->getOrganizationArgument();

        if (! $organizationInput) {
            $this->error('Organization is required. Either pass the organization argument or set FORGE_ORGANIZATION in your environment.');

            return self::FAILURE;
        }

        try {
            $organization = $this->resolveOrganizationSlug($organizationInput, $forge);
        } catch (InvalidArgumentException $e) {
            $this->error($e->getMessage());

            return self::FAILURE;
        }

        $name = $this->option('name');
        $provider = $this->option('provider');
        $credential = $this->option('credential');
        $region = $this->option('region');
        $size = $this->option('size');
        $type = $this->option('type');
        $ubuntuVersion = $this->option('ubuntu-version');
        $phpVersion = $this->option('php-version') ?? config('forge-sdk.default_php_version');
        $database = $this->option('database') ?? config('forge-sdk.default_database');

        // Validate required options
        if (! $name) {
            $this->error('Missing required option: --name');

            return self::FAILURE;
        }

        if (! $provider) {
            $this->error('Missing required option: --provider (e.g., laravel, ocean2, hetzner)');

            return self::FAILURE;
        }

        if (! $credential) {
            $this->error('Missing required option: --credential (use forge:list-server-credentials to find IDs)');

            return self::FAILURE;
        }

        if (! $size) {
            $this->error('Missing required option: --size (use forge:list-provider-sizes to find IDs)');

            return self::FAILURE;
        }

        // Region is required for all providers
        if (! $region) {
            $this->error('Missing required option: --region (use forge:list-provider-regions to find region codes)');

            return self::FAILURE;
        }

        $this->info('Creating server with the following configuration:');
        $this->line("  Organization: {$organization}");
        $this->line("  Name: {$name}");
        $this->line("  Provider: {$provider}");
        $this->line("  Credential ID: {$credential}");
        $this->line("  Type: {$type}");
        $this->line("  Ubuntu Version: {$ubuntuVersion}");

        if ($region) {
            $this->line("  Region: {$region}");
        }

        $this->line("  Size ID: {$size}");

        if ($phpVersion) {
            $this->line("  PHP Version: {$phpVersion}");
        }

        if ($database) {
            $this->line("  Database: {$database}");
        }

        $this->newLine();

        if (! $this->confirmOperation('Are you sure you want to create this server?')) {
            $this->info('Operation cancelled.');

            return self::SUCCESS;
        }

        // Build base request body
        $body = [
            'name' => $name,
            'provider' => $provider,
            'credential_id' => (int) $credential,
            'type' => $type,
            'ubuntu_version' => $ubuntuVersion,
        ];

        // Add optional fields
        if ($phpVersion) {
            $body['php_version'] = $phpVersion;
        }

        if ($database) {
            $body['database_type'] = $database;
        }

        // Build provider-specific configuration
        $providerConfig = [
            'size_id' => (int) $size,
        ];

        if ($region) {
            $providerConfig['region_id'] = $region;
        }

        // Add provider-specific config to body
        $body[$provider] = $providerConfig;

        $this->logOperation('Create server', [
            'organization' => $organization,
            'name' => $name,
            'provider' => $provider,
        ], 'warning');

        try {
            $request = new OrganizationsServersStore($organization);
            $request->body()->merge($body);

            $response = $forge->send($request);

            if (! $response->successful()) {
                $this->logError('Create server', $response->body(), [
                    'status' => $response->status(),
                    'organization' => $organization,
                ]);
                $this->error("Failed to create server: {$response->body()}");

                return self::FAILURE;
            }

            $data = $response->json();
            $server = $data['data'] ?? $data;
            $serverAttributes = $server['attributes'] ?? $server;
            $serverMeta = $server['meta'] ?? [];

            // Debug: dump full response if verbose
            if ($this->output->isVerbose()) {
                $this->newLine();
                $this->comment('Full API Response:');
                $this->line(json_encode($data, JSON_PRETTY_PRINT));
                $this->newLine();
            }

            $this->info('Server created successfully!');
            $this->line("ID: {$server['id']}");
            $this->line("Name: ".($serverAttributes['name'] ?? 'N/A'));
            $this->line("Status: ".($serverAttributes['status'] ?? 'provisioning'));

            // Display credentials from meta
            if (isset($serverMeta['sudo_password']) || isset($serverMeta['database_password'])) {
                $this->newLine();
                $this->warn('⚠️  IMPORTANT: Save these credentials securely! They will not be shown again.');
                $this->newLine();

                if (isset($serverMeta['sudo_password'])) {
                    $this->line("Sudo Password: <fg=yellow>{$serverMeta['sudo_password']}</>");
                }

                if (isset($serverMeta['database_password'])) {
                    $this->line("Database Password (user: forge): <fg=yellow>{$serverMeta['database_password']}</>");
                }
            }

            $executionTime = round((microtime(true) - $startTime) * 1000, 2);

            $this->logSuccess('Create server', [
                'organization' => $organization,
                'server_id' => $server['id'],
                'server_name' => $serverAttributes['name'] ?? $name,
                'execution_time_ms' => $executionTime,
            ]);

            $this->newLine();
            $this->comment("Server creation initiated in {$executionTime}ms");
            $this->info('Note: Server provisioning is asynchronous. Use forge:get-server to check status.');

            return self::SUCCESS;
        } catch (Exception $e) {
            $executionTime = round((microtime(true) - $startTime) * 1000, 2);

            $this->logError('Create server', $e->getMessage(), [
                'organization' => $organization,
                'execution_time_ms' => $executionTime,
            ]);

            $this->error("Error: {$e->getMessage()}");

            return self::FAILURE;
        }
    }
}
