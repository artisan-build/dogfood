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

class RestartBackgroundProcessCommand extends Command
{
    use HandlesDefaultArguments;
    use LogsForgeOperations;
    use PerformsDestructiveForgeOperations;
    use ResolvesResourceIdentifiers;

    protected $signature = 'forge:restart-background-process
                            {organization? : The organization slug or ID}
                            {server? : The server name or ID}
                            {background-process? : The background process ID}
                            {--dangerously-skip-confirmation : Skip confirmation prompt}';

    protected $description = 'Restart a background process';

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
        $backgroundProcessId = (int) $this->argument('background-process');

        try {
            $organization = $this->resolveOrganizationSlug($organizationInput, $forge);
            $serverId = $this->resolveServerId($serverInput, $organization, $forge);
        } catch (InvalidArgumentException $e) {
            $this->error($e->getMessage());

            return self::FAILURE;
        }

        // Get background process details for confirmation
        try {
            $processResponse = $forge->backgroundProcesses()->organizationsServersBackgroundProcessesShow(
                organization: $organization,
                server: $serverId,
                backgroundProcess: $backgroundProcessId
            );

            if ($processResponse->successful()) {
                $processData = $processResponse->json();
                $process = $processData['data'] ?? $processData;
                $processCommand = $process['command'] ?? "Background Process {$backgroundProcessId}";

                $this->info('Restarting background process:');
                $this->line("  Command: {$processCommand}");
                $this->line("  ID: {$backgroundProcessId}");
                $this->newLine();

                if (! $this->confirmOperation("Are you sure you want to restart '{$processCommand}'?")) {
                    $this->info('Operation cancelled.');

                    return self::SUCCESS;
                }
            } else {
                if (! $this->confirmOperation("Are you sure you want to restart background process {$backgroundProcessId}?")) {
                    $this->info('Operation cancelled.');

                    return self::SUCCESS;
                }
            }
        } catch (Exception $e) {
            // If we can't get process details, fail the operation for safety
            $executionTime = round((microtime(true) - $startTime) * 1000, 2);

            $this->logError('Get background process details before restart', $e->getMessage(), [
                'organization' => $organization,
                'server_id' => $serverId,
                'background_process_id' => $backgroundProcessId,
                'execution_time_ms' => $executionTime,
            ]);

            $this->error("Unable to retrieve background process details: {$e->getMessage()}");
            $this->error('Cannot safely restart background process without confirming details.');

            return self::FAILURE;
        }

        $this->logOperation('Restart background process', [
            'organization' => $organization,
            'server_id' => $serverId,
            'background_process_id' => $backgroundProcessId,
        ], 'warning');

        try {
            // Restart is triggered via the update endpoint
            $response = $forge->backgroundProcesses()->organizationsServersBackgroundProcessesUpdate(
                organization: $organization,
                server: $serverId,
                backgroundProcess: $backgroundProcessId
            );

            if (! $response->successful()) {
                $this->logError('Restart background process', $response->body(), [
                    'status' => $response->status(),
                    'organization' => $organization,
                    'server_id' => $serverId,
                    'background_process_id' => $backgroundProcessId,
                ]);
                $this->error("Failed to restart background process: {$response->body()}");

                return self::FAILURE;
            }

            $executionTime = round((microtime(true) - $startTime) * 1000, 2);

            $this->logSuccess('Restart background process', [
                'organization' => $organization,
                'server_id' => $serverId,
                'background_process_id' => $backgroundProcessId,
                'execution_time_ms' => $executionTime,
            ]);

            $this->info("Background process {$backgroundProcessId} is restarting... (completed in {$executionTime}ms)");

            return self::SUCCESS;
        } catch (Exception $e) {
            $executionTime = round((microtime(true) - $startTime) * 1000, 2);

            $this->logError('Restart background process', $e->getMessage(), [
                'organization' => $organization,
                'server_id' => $serverId,
                'background_process_id' => $backgroundProcessId,
                'execution_time_ms' => $executionTime,
            ]);

            $this->error("Error: {$e->getMessage()}");

            return self::FAILURE;
        }
    }
}
