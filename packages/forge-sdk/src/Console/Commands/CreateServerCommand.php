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

class CreateServerCommand extends Command
{
    use HandlesDefaultArguments;
    use LogsForgeOperations;
    use PerformsDestructiveForgeOperations;
    use ResolvesResourceIdentifiers;

    protected $signature = 'forge:create-server
                            {organization? : The organization slug or ID}
                            {--name= : Server name}
                            {--credential= : Server credential ID}
                            {--region= : Server region}
                            {--size= : Server size}
                            {--php-version= : PHP version}
                            {--database= : Database type (mysql8, postgres, mariadb, none)}
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
        } catch (\InvalidArgumentException $e) {
            $this->error($e->getMessage());

            return self::FAILURE;
        }
        $name = $this->option('name');
        $credential = $this->option('credential');
        $region = $this->option('region');
        $size = $this->option('size');
        $phpVersion = $this->option('php-version');
        $database = $this->option('database') ?? 'none';

        // Validate required options
        if (! $name || ! $credential || ! $region || ! $size) {
            $this->error('Missing required options: --name, --credential, --region, and --size are required');

            return self::FAILURE;
        }

        $this->info('Creating server with the following configuration:');
        $this->line("  Organization: {$organization}");
        $this->line("  Name: {$name}");
        $this->line("  Credential ID: {$credential}");
        $this->line("  Region: {$region}");
        $this->line("  Size: {$size}");

        if ($phpVersion) {
            $this->line("  PHP Version: {$phpVersion}");
        }

        $this->line("  Database: {$database}");
        $this->newLine();

        if (! $this->confirmOperation('Are you sure you want to create this server?')) {
            $this->info('Operation cancelled.');

            return self::SUCCESS;
        }

        $body = [
            'name' => $name,
            'credential_id' => (int) $credential,
            'region' => $region,
            'size' => $size,
            'database' => $database,
        ];

        if ($phpVersion) {
            $body['php_version'] = $phpVersion;
        }

        $this->logOperation('Create server', [
            'organization' => $organization,
            'name' => $name,
            'region' => $region,
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

            $this->info('Server created successfully!');
            $this->line("ID: {$server['id']}");
            $this->line("Name: {$server['name']}");
            $this->line("Status: {$server['status']}");

            $executionTime = round((microtime(true) - $startTime) * 1000, 2);

            $this->logSuccess('Create server', [
                'organization' => $organization,
                'server_id' => $server['id'],
                'server_name' => $server['name'],
                'execution_time_ms' => $executionTime,
            ]);

            $this->newLine();
            $this->comment("Server created in {$executionTime}ms");

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
