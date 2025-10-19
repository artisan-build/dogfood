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

class GetDatabaseCommand extends Command
{
    use HandlesDefaultArguments;
    use LogsForgeOperations;
    use ResolvesResourceIdentifiers;

    protected $signature = 'forge:get-database
                            {organization? : The organization slug or ID}
                            {server? : The server name or ID}
                            {database? : The database ID}';

    protected $description = 'Get details for a specific database schema';

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
        } catch (InvalidArgumentException $e) {
            $this->error($e->getMessage());

            return self::FAILURE;
        }

        $this->logOperation('Get database', [
            'organization' => $organization,
            'server_id' => $serverId,
            'database_id' => $databaseId,
        ]);

        try {
            $response = $forge->databases()->organizationsServersDatabaseSchemasShow(
                organization: $organization,
                server: $serverId,
                database: $databaseId
            );

            if (! $response->successful()) {
                $this->logError('Get database', $response->body(), [
                    'status' => $response->status(),
                    'organization' => $organization,
                    'server_id' => $serverId,
                    'database_id' => $databaseId,
                ]);
                $this->error("Failed to get database: {$response->body()}");

                return self::FAILURE;
            }

            $data = $response->json();
            $database = $data['data'] ?? $data;

            $this->table(
                ['Property', 'Value'],
                [
                    ['ID', $database['id'] ?? 'N/A'],
                    ['Name', $database['name'] ?? 'N/A'],
                    ['Status', $database['status'] ?? 'N/A'],
                    ['Created At', $database['created_at'] ?? 'N/A'],
                    ['Updated At', $database['updated_at'] ?? 'N/A'],
                ]
            );

            $executionTime = round((microtime(true) - $startTime) * 1000, 2);

            $this->logSuccess('Get database', [
                'organization' => $organization,
                'server_id' => $serverId,
                'database_id' => $databaseId,
                'execution_time_ms' => $executionTime,
            ]);

            $this->info("Retrieved database details in {$executionTime}ms");

            return self::SUCCESS;
        } catch (Exception $e) {
            $executionTime = round((microtime(true) - $startTime) * 1000, 2);

            $this->logError('Get database', $e->getMessage(), [
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
