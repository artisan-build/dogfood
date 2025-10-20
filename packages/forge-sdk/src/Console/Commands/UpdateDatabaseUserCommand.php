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

class UpdateDatabaseUserCommand extends Command
{
    use HandlesDefaultArguments;
    use LogsForgeOperations;
    use PerformsDestructiveForgeOperations;
    use ResolvesResourceIdentifiers;

    protected $signature = 'forge:update-database-user
                            {database-user? : The database user ID}
                            {server? : The server name or ID}
                            {organization? : The organization slug or ID}
                            {--dangerously-skip-confirmation : Skip confirmation prompt}';

    protected $description = 'Update a database user';

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

        $this->warn('You are about to UPDATE the database user.');
        $this->line("  Organization: {$organization}");
        $this->line("  Server ID: {$serverId}");
        $this->line("  Database User ID: {$databaseUserId}");
        $this->newLine();

        if (! $this->confirmOperation('Do you want to proceed with updating this database user?')) {
            $this->info('Operation cancelled.');

            return self::SUCCESS;
        }

        $this->logOperation('Update database user', [
            'organization' => $organization,
            'server_id' => $serverId,
            'database_user_id' => $databaseUserId,
        ], 'warning');

        try {
            $response = $forge->databases()->organizationsServersDatabaseUsersUpdate(
                organization: $organization,
                server: $serverId,
                databaseUser: $databaseUserId
            );

            if (! $response->successful()) {
                $this->logError('Update database user', $response->body(), [
                    'status' => $response->status(),
                    'organization' => $organization,
                    'server_id' => $serverId,
                    'database_user_id' => $databaseUserId,
                ]);
                $this->error("Failed to update database user: {$response->body()}");

                return self::FAILURE;
            }

            $data = $response->json();
            $user = $data['data'] ?? $data;

            $executionTime = round((microtime(true) - $startTime) * 1000, 2);

            $this->logSuccess('Update database user', [
                'organization' => $organization,
                'server_id' => $serverId,
                'database_user_id' => $databaseUserId,
                'execution_time_ms' => $executionTime,
            ]);

            $this->info("Database user updated successfully in {$executionTime}ms");
            $this->table(
                ['Property', 'Value'],
                [
                    ['ID', $user['id'] ?? 'N/A'],
                    ['Name', $user['name'] ?? 'N/A'],
                    ['Status', $user['status'] ?? 'N/A'],
                ]
            );

            return self::SUCCESS;
        } catch (Exception $e) {
            $executionTime = round((microtime(true) - $startTime) * 1000, 2);

            $this->logError('Update database user', $e->getMessage(), [
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
