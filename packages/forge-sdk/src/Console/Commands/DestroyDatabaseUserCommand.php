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

class DestroyDatabaseUserCommand extends Command
{
    use HandlesDefaultArguments;
    use LogsForgeOperations;
    use PerformsDestructiveForgeOperations;
    use ResolvesResourceIdentifiers;

    protected $signature = 'forge:destroy-database-user
                            {database-user? : The database user ID}
                            {server? : The server name or ID}
                            {organization? : The organization slug or ID}
                            {--dangerously-skip-confirmation : Skip confirmation prompt}';

    protected $description = 'Destroy (delete) a database user';

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

        // Get database user details first for confirmation message
        try {
            $userResponse = $forge->databases()->organizationsServersDatabaseUsersShow(
                organization: $organization,
                server: $serverId,
                databaseUser: $databaseUserId
            );

            if (! $userResponse->successful()) {
                $this->error("Failed to get database user details: {$userResponse->body()}");

                return self::FAILURE;
            }

            $userData = $userResponse->json();
            $user = $userData['data'] ?? $userData;
            $userName = $user['name'] ?? "User {$databaseUserId}";

            $this->warn('You are about to DESTROY the following database user:');
            $this->line("  ID: {$databaseUserId}");
            $this->line("  Name: {$userName}");
            $this->line("  Organization: {$organization}");
            $this->line("  Server ID: {$serverId}");
            $this->newLine();
            $this->error('This action is IRREVERSIBLE and will permanently delete the database user.');
            $this->newLine();

            if (! $this->confirmOperation("Type 'yes' to confirm you want to destroy database user '{$userName}'")) {
                $this->info('Operation cancelled.');

                return self::SUCCESS;
            }
        } catch (Exception $e) {
            $executionTime = round((microtime(true) - $startTime) * 1000, 2);

            $this->logError('Get database user details before destroy', $e->getMessage(), [
                'organization' => $organization,
                'server_id' => $serverId,
                'database_user_id' => $databaseUserId,
                'execution_time_ms' => $executionTime,
            ]);

            $this->error("Unable to retrieve database user details: {$e->getMessage()}");
            $this->error('Cannot safely destroy database user without confirming details.');

            return self::FAILURE;
        }

        $this->logOperation('Destroy database user', [
            'organization' => $organization,
            'server_id' => $serverId,
            'database_user_id' => $databaseUserId,
        ], 'error'); // Error level for audit trail

        try {
            $response = $forge->databases()->organizationsServersDatabaseUsersDestroy(
                organization: $organization,
                server: $serverId,
                databaseUser: $databaseUserId
            );

            if (! $response->successful()) {
                $this->logError('Destroy database user', $response->body(), [
                    'status' => $response->status(),
                    'organization' => $organization,
                    'server_id' => $serverId,
                    'database_user_id' => $databaseUserId,
                ]);
                $this->error("Failed to destroy database user: {$response->body()}");

                return self::FAILURE;
            }

            $executionTime = round((microtime(true) - $startTime) * 1000, 2);

            $this->logSuccess('Destroy database user', [
                'organization' => $organization,
                'server_id' => $serverId,
                'database_user_id' => $databaseUserId,
                'execution_time_ms' => $executionTime,
            ]);

            $this->info("Database user destroyed successfully in {$executionTime}ms");

            return self::SUCCESS;
        } catch (Exception $e) {
            $executionTime = round((microtime(true) - $startTime) * 1000, 2);

            $this->logError('Destroy database user', $e->getMessage(), [
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
