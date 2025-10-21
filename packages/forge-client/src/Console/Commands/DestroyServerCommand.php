<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeClient\Console\Commands;

use ArtisanBuild\ForgeClient\Console\Concerns\HandlesDefaultArguments;
use ArtisanBuild\ForgeClient\Console\Concerns\LogsForgeOperations;
use ArtisanBuild\ForgeClient\Console\Concerns\PerformsDestructiveForgeOperations;
use ArtisanBuild\ForgeClient\Console\Concerns\ResolvesResourceIdentifiers;
use ArtisanBuild\ForgeClient\Exceptions\ProtectedResourceException;
use ArtisanBuild\ForgeClient\ForgeClient;
use Exception;
use Illuminate\Console\Command;
use InvalidArgumentException;

class DestroyServerCommand extends Command
{
    use HandlesDefaultArguments;
    use LogsForgeOperations;
    use PerformsDestructiveForgeOperations;
    use ResolvesResourceIdentifiers;

    protected $signature = 'forge:destroy-server
                            {server? : The server name or ID}
                            {organization? : The organization slug or ID}
                            {--dangerously-skip-confirmation : Skip confirmation prompt}';

    protected $description = 'Destroy (delete) a server';

    public function handle(ForgeClient $forge): int
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
            $serverId = $this->resolveServerId($serverInput, $organization, $forge);
        } catch (InvalidArgumentException $e) {
            $this->error($e->getMessage());

            return self::FAILURE;
        }

        // Get server details first for confirmation message
        try {
            $serverResponse = $forge->servers()->organizationsServersShow($organization, $serverId);

            if (! $serverResponse->successful()) {
                $this->error("Failed to get server details: {$serverResponse->body()}");

                return self::FAILURE;
            }

            $serverData = $serverResponse->json();
            $server = $serverData['data'] ?? $serverData;
            $serverName = $server['attributes']['name'] ?? "Server {$serverId}";

            $this->warn('You are about to DESTROY the following server:');
            $this->line("  ID: {$serverId}");
            $this->line("  Name: {$serverName}");
            $this->line("  Organization: {$organization}");
            $this->newLine();
            $this->error('This action is IRREVERSIBLE and will permanently delete the server.');
            $this->newLine();

            if (! $this->confirmOperation("Type 'yes' to confirm you want to destroy server '{$serverName}'")) {
                $this->info('Operation cancelled.');

                return self::SUCCESS;
            }
        } catch (Exception $e) {
            // If we can't get server details, fail the operation for safety
            $executionTime = round((microtime(true) - $startTime) * 1000, 2);

            $this->logError('Get server details before destroy', $e->getMessage(), [
                'organization' => $organization,
                'server_id' => $serverId,
                'execution_time_ms' => $executionTime,
            ]);

            $this->error("Unable to retrieve server details: {$e->getMessage()}");
            $this->error('Cannot safely destroy server without confirming details.');

            return self::FAILURE;
        }

        $this->logOperation('Destroy server', [
            'organization' => $organization,
            'server_id' => $serverId,
        ], 'error'); // Error level for audit trail

        try {
            $response = $forge->servers()->organizationsServersDestroy($organization, $serverId);

            if (! $response->successful()) {
                $this->logError('Destroy server', $response->body(), [
                    'status' => $response->status(),
                    'organization' => $organization,
                    'server_id' => $serverId,
                ]);
                $this->error("Failed to destroy server: {$response->body()}");

                return self::FAILURE;
            }

            $executionTime = round((microtime(true) - $startTime) * 1000, 2);

            $this->logSuccess('Destroy server', [
                'organization' => $organization,
                'server_id' => $serverId,
                'execution_time_ms' => $executionTime,
            ]);

            $this->info("Server {$serverId} destroyed successfully in {$executionTime}ms");

            return self::SUCCESS;
        } catch (ProtectedResourceException $e) {
            $this->newLine();
            $this->error('PROTECTED RESOURCE');
            $this->newLine();
            $this->line("Server {$serverId} is marked as protected because it is critical to your business operations.");
            $this->line('This server cannot be deleted via the SDK to prevent accidental data loss.');
            $this->newLine();
            $this->comment('What to do:');
            $this->line('  • If this server has been replaced, update config/forge-client.php');
            $this->line("  • Remove server ID {$serverId} from the 'protected_servers' array");
            $this->line("  • Add the new server's ID to 'protected_servers' if needed");
            $this->line('  • If you still need to delete this server, do so through the Forge UI');
            $this->newLine();

            $this->logError('Protected server deletion attempt', $e->getMessage(), [
                'organization' => $organization,
                'server_id' => $serverId,
            ]);

            return self::FAILURE;
        } catch (Exception $e) {
            $executionTime = round((microtime(true) - $startTime) * 1000, 2);

            $this->logError('Destroy server', $e->getMessage(), [
                'organization' => $organization,
                'server_id' => $serverId,
                'execution_time_ms' => $executionTime,
            ]);

            $this->error("Error: {$e->getMessage()}");

            return self::FAILURE;
        }
    }
}
