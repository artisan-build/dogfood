<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeSdk\Console\Commands;

use ArtisanBuild\ForgeSdk\Console\Concerns\HandlesDefaultArguments;
use ArtisanBuild\ForgeSdk\Console\Concerns\LogsForgeOperations;
use ArtisanBuild\ForgeSdk\Console\Concerns\PerformsDestructiveForgeOperations;
use ArtisanBuild\ForgeSdk\Console\Concerns\ResolvesResourceIdentifiers;
use ArtisanBuild\ForgeSdk\ForgeSdk;
use ArtisanBuild\ForgeSdk\Requests\Servers\OrganizationsServersActionsStore;
use Exception;
use Illuminate\Console\Command;
use InvalidArgumentException;

class RebootServerCommand extends Command
{
    use HandlesDefaultArguments;
    use LogsForgeOperations;
    use PerformsDestructiveForgeOperations;
    use ResolvesResourceIdentifiers;

    protected $signature = 'forge:reboot-server
                            {server? : The server name or ID}
                            {organization? : The organization slug or ID}
                            {--dangerously-skip-confirmation : Skip confirmation prompt}';

    protected $description = 'Reboot a server';

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
            $serverId = $this->resolveServerId($serverInput, $organization, $forge);
        } catch (InvalidArgumentException $e) {
            $this->error($e->getMessage());

            return self::FAILURE;
        }

        // Get server details for confirmation
        try {
            $serverResponse = $forge->servers()->organizationsServersShow($organization, $serverId);

            if ($serverResponse->successful()) {
                $serverData = $serverResponse->json();
                $server = $serverData['data'] ?? $serverData;
                $serverName = $server['name'] ?? "Server {$serverId}";

                $this->info('Rebooting server:');
                $this->line("  Name: {$serverName}");
                $this->line("  ID: {$serverId}");
                $this->newLine();

                if (! $this->confirmOperation("Are you sure you want to reboot '{$serverName}'?")) {
                    $this->info('Operation cancelled.');

                    return self::SUCCESS;
                }
            } else {
                if (! $this->confirmOperation("Are you sure you want to reboot server {$serverId}?")) {
                    $this->info('Operation cancelled.');

                    return self::SUCCESS;
                }
            }
        } catch (Exception $e) {
            // If we can't get server details, fail the operation for safety
            $executionTime = round((microtime(true) - $startTime) * 1000, 2);

            $this->logError('Get server details before reboot', $e->getMessage(), [
                'organization' => $organization,
                'server_id' => $serverId,
                'execution_time_ms' => $executionTime,
            ]);

            $this->error("Unable to retrieve server details: {$e->getMessage()}");
            $this->error('Cannot safely reboot server without confirming details.');

            return self::FAILURE;
        }

        $this->logOperation('Reboot server', [
            'organization' => $organization,
            'server_id' => $serverId,
        ], 'warning');

        try {
            $request = new OrganizationsServersActionsStore($organization, $serverId);
            $request->body()->merge(['action' => 'reboot']);

            $response = $forge->send($request);

            if (! $response->successful()) {
                $this->logError('Reboot server', $response->body(), [
                    'status' => $response->status(),
                    'organization' => $organization,
                    'server_id' => $serverId,
                ]);
                $this->error("Failed to reboot server: {$response->body()}");

                return self::FAILURE;
            }

            $executionTime = round((microtime(true) - $startTime) * 1000, 2);

            $this->logSuccess('Reboot server', [
                'organization' => $organization,
                'server_id' => $serverId,
                'execution_time_ms' => $executionTime,
            ]);

            $this->info("Server {$serverId} is rebooting... (completed in {$executionTime}ms)");

            return self::SUCCESS;
        } catch (Exception $e) {
            $executionTime = round((microtime(true) - $startTime) * 1000, 2);

            $this->logError('Reboot server', $e->getMessage(), [
                'organization' => $organization,
                'server_id' => $serverId,
                'execution_time_ms' => $executionTime,
            ]);

            $this->error("Error: {$e->getMessage()}");

            return self::FAILURE;
        }
    }
}
