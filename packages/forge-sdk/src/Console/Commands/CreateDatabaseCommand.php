<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeSdk\Console\Commands;

use ArtisanBuild\ForgeSdk\Console\Concerns\HandlesDefaultArguments;
use ArtisanBuild\ForgeSdk\Console\Concerns\LogsForgeOperations;
use ArtisanBuild\ForgeSdk\Console\Concerns\PerformsDestructiveForgeOperations;
use ArtisanBuild\ForgeSdk\Console\Concerns\ResolvesResourceIdentifiers;
use ArtisanBuild\ForgeSdk\ForgeSdk;
use Exception;
use Illuminate\Console\Command;
use InvalidArgumentException;

class CreateDatabaseCommand extends Command
{
    use HandlesDefaultArguments;
    use LogsForgeOperations;
    use PerformsDestructiveForgeOperations;
    use ResolvesResourceIdentifiers;

    protected $signature = 'forge:create-database
                            {name : The database name}
                            {server? : The server name or ID}
                            {organization? : The organization slug or ID}
                            {--user= : Optional database user to create}
                            {--password= : Password for the database user (required if user is provided)}
                            {--dangerously-skip-confirmation : Skip confirmation prompt}';

    protected $description = 'Create a new database schema on a server';

    public function handle(ForgeSdk $forge): int
    {
        $startTime = microtime(true);

        $name = $this->argument('name');
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

        $user = $this->option('user');
        $password = $this->option('password');

        // Validate that if user is provided, password must also be provided
        if ($user && ! $password) {
            $this->error('Password is required when creating a database user.');

            return self::FAILURE;
        }

        try {
            $organization = $this->resolveOrganizationSlug($organizationInput, $forge);
            $serverId = $this->resolveServerId($serverInput, $organization, $forge);
        } catch (InvalidArgumentException $e) {
            $this->error($e->getMessage());

            return self::FAILURE;
        }

        $this->warn('You are about to CREATE a new database on the server.');
        $this->line("  Database Name: {$name}");
        $this->line("  Organization: {$organization}");
        $this->line("  Server ID: {$serverId}");
        if ($user) {
            $this->line("  User: {$user}");
        }
        $this->newLine();

        if (! $this->confirmOperation('Do you want to proceed with creating this database?')) {
            $this->info('Operation cancelled.');

            return self::SUCCESS;
        }

        $this->logOperation('Create database', [
            'organization' => $organization,
            'server_id' => $serverId,
            'name' => $name,
        ], 'warning');

        try {
            $body = ['name' => $name];
            if ($user) {
                $body['user'] = $user;
                $body['password'] = $password;
            }

            $response = $forge->databases()->organizationsServersDatabaseSchemasStore(
                organization: $organization,
                server: $serverId,
                body: $body
            );

            if (! $response->successful()) {
                $this->logError('Create database', $response->body(), [
                    'status' => $response->status(),
                    'organization' => $organization,
                    'server_id' => $serverId,
                ]);
                $this->error("Failed to create database: {$response->body()}");

                return self::FAILURE;
            }

            $data = $response->json();
            $database = $data['data'] ?? $data;

            $executionTime = round((microtime(true) - $startTime) * 1000, 2);

            $this->logSuccess('Create database', [
                'organization' => $organization,
                'server_id' => $serverId,
                'database_id' => $database['id'] ?? 'N/A',
                'execution_time_ms' => $executionTime,
            ]);

            $this->info("Database created successfully in {$executionTime}ms");
            $this->table(
                ['Property', 'Value'],
                [
                    ['ID', $database['id'] ?? 'N/A'],
                    ['Name', $database['name'] ?? 'N/A'],
                    ['Status', $database['status'] ?? 'N/A'],
                ]
            );

            return self::SUCCESS;
        } catch (Exception $e) {
            $executionTime = round((microtime(true) - $startTime) * 1000, 2);

            $this->logError('Create database', $e->getMessage(), [
                'organization' => $organization,
                'server_id' => $serverId,
                'execution_time_ms' => $executionTime,
            ]);

            $this->error("Error: {$e->getMessage()}");

            return self::FAILURE;
        }
    }
}
