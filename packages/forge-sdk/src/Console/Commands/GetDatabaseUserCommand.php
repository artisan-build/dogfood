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

class GetDatabaseUserCommand extends Command
{
    use HandlesDefaultArguments;
    use LogsForgeOperations;
    use ResolvesResourceIdentifiers;

    protected $signature = 'forge:get-database-user
                            {database-user? : The database user ID}
                            {server? : The server name or ID}
                            {organization? : The organization slug or ID}';

    protected $description = 'Get details for a specific database user';

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
        $databaseUserId = (int) $this->argument('database-user');

        try {
            $organization = $this->resolveOrganizationSlug($organizationInput, $forge);
            $serverId = $this->resolveServerId($serverInput, $organization, $forge);
        } catch (InvalidArgumentException $e) {
            $this->error($e->getMessage());

            return self::FAILURE;
        }

        $this->logOperation('Get database user', [
            'organization' => $organization,
            'server_id' => $serverId,
            'database_user_id' => $databaseUserId,
        ]);

        try {
            $response = $forge->databases()->organizationsServersDatabaseUsersShow(
                organization: $organization,
                server: $serverId,
                databaseUser: $databaseUserId
            );

            if (! $response->successful()) {
                $this->logError('Get database user', $response->body(), [
                    'status' => $response->status(),
                    'organization' => $organization,
                    'server_id' => $serverId,
                    'database_user_id' => $databaseUserId,
                ]);
                $this->error("Failed to get database user: {$response->body()}");

                return self::FAILURE;
            }

            $data = $response->json();
            $user = $data['data'] ?? $data;

            $this->table(
                ['Property', 'Value'],
                [
                    ['ID', $user['id'] ?? 'N/A'],
                    ['Name', $user['name'] ?? 'N/A'],
                    ['Status', $user['status'] ?? 'N/A'],
                    ['Created At', $user['created_at'] ?? 'N/A'],
                    ['Updated At', $user['updated_at'] ?? 'N/A'],
                ]
            );

            $executionTime = round((microtime(true) - $startTime) * 1000, 2);

            $this->logSuccess('Get database user', [
                'organization' => $organization,
                'server_id' => $serverId,
                'database_user_id' => $databaseUserId,
                'execution_time_ms' => $executionTime,
            ]);

            $this->info("Retrieved database user details in {$executionTime}ms");

            return self::SUCCESS;
        } catch (Exception $e) {
            $executionTime = round((microtime(true) - $startTime) * 1000, 2);

            $this->logError('Get database user', $e->getMessage(), [
                'organization' => $organization,
                'server_id' => $serverId,
                'database_user_id' => $databaseUserId,
                'execution_time_ms' => $executionTime,
            ]);

            $this->error("Error: {$e->getMessage()}");

            return self::FAILURE;
        }
    }
}
