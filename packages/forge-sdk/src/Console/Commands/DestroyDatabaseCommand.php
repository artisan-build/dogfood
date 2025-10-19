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

class DestroyDatabaseCommand extends Command
{
    use HandlesDefaultArguments;
    use LogsForgeOperations;
    use PerformsDestructiveForgeOperations;
    use ResolvesResourceIdentifiers;

    protected $signature = 'forge:destroy-database
                            {organization? : The organization slug or ID}
                            {server? : The server name or ID}
                            {database? : The database ID}
                            {--dangerously-skip-confirmation : Skip confirmation prompt}';

    protected $description = 'Destroy (delete) a database schema';

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
        $databaseId = (int) $this->argument('database');

        try {
            $organization = $this->resolveOrganizationSlug($organizationInput, $forge);
            $serverId = $this->resolveServerId($serverInput, $organization, $forge);
        } catch (\InvalidArgumentException $e) {
            $this->error($e->getMessage());

            return self::FAILURE;
        }

        // Get database details first for confirmation message
        try {
            $databaseResponse = $forge->databases()->organizationsServersDatabaseSchemasShow(
                organization: $organization,
                server: $serverId,
                database: $databaseId
            );

            if (! $databaseResponse->successful()) {
                $this->error("Failed to get database details: {$databaseResponse->body()}");

                return self::FAILURE;
            }

            $databaseData = $databaseResponse->json();
            $database = $databaseData['data'] ?? $databaseData;
            $databaseName = $database['name'] ?? "Database {$databaseId}";

            $this->warn('You are about to DESTROY the following database:');
            $this->line("  ID: {$databaseId}");
            $this->line("  Name: {$databaseName}");
            $this->line("  Organization: {$organization}");
            $this->line("  Server ID: {$serverId}");
            $this->newLine();
            $this->error('This action is IRREVERSIBLE and will permanently delete the database.');
            $this->newLine();

            if (! $this->confirmOperation("Type 'yes' to confirm you want to destroy database '{$databaseName}'")) {
                $this->info('Operation cancelled.');

                return self::SUCCESS;
            }
        } catch (Exception $e) {
            $executionTime = round((microtime(true) - $startTime) * 1000, 2);

            $this->logError('Get database details before destroy', $e->getMessage(), [
                'organization' => $organization,
                'server_id' => $serverId,
                'database_id' => $databaseId,
                'execution_time_ms' => $executionTime,
            ]);

            $this->error("Unable to retrieve database details: {$e->getMessage()}");
            $this->error('Cannot safely destroy database without confirming details.');

            return self::FAILURE;
        }

        $this->logOperation('Destroy database', [
            'organization' => $organization,
            'server_id' => $serverId,
            'database_id' => $databaseId,
        ], 'error'); // Error level for audit trail

        try {
            $response = $forge->databases()->organizationsServersDatabaseSchemasDestroy(
                organization: $organization,
                server: $serverId,
                database: $databaseId
            );

            if (! $response->successful()) {
                $this->logError('Destroy database', $response->body(), [
                    'status' => $response->status(),
                    'organization' => $organization,
                    'server_id' => $serverId,
                    'database_id' => $databaseId,
                ]);
                $this->error("Failed to destroy database: {$response->body()}");

                return self::FAILURE;
            }

            $executionTime = round((microtime(true) - $startTime) * 1000, 2);

            $this->logSuccess('Destroy database', [
                'organization' => $organization,
                'server_id' => $serverId,
                'database_id' => $databaseId,
                'execution_time_ms' => $executionTime,
            ]);

            $this->info("Database destroyed successfully in {$executionTime}ms");

            return self::SUCCESS;
        } catch (Exception $e) {
            $executionTime = round((microtime(true) - $startTime) * 1000, 2);

            $this->logError('Destroy database', $e->getMessage(), [
                'organization' => $organization,
                'server_id' => $serverId,
                'database_id' => $databaseId,
                'execution_time_ms' => $executionTime,
            ]);

            $this->error("Error: {$e->getMessage()}");

            return self::FAILURE;
        }
    }
}
