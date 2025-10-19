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

class DestroyBackgroundProcessCommand extends Command
{
    use HandlesDefaultArguments;
    use LogsForgeOperations;
    use PerformsDestructiveForgeOperations;
    use ResolvesResourceIdentifiers;

    protected $signature = 'forge:destroy-background-process
                            {organization? : The organization slug or ID}
                            {server? : The server name or ID}
                            {background-process? : The background process ID}
                            {--dangerously-skip-confirmation : Skip confirmation prompt}';

    protected $description = 'Destroy (delete) a background process';

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

        // Get background process details first for confirmation message
        try {
            $processResponse = $forge->backgroundProcesses()->organizationsServersBackgroundProcessesShow(
                organization: $organization,
                server: $serverId,
                backgroundProcess: $backgroundProcessId
            );

            if (! $processResponse->successful()) {
                $this->error("Failed to get background process details: {$processResponse->body()}");

                return self::FAILURE;
            }

            $processData = $processResponse->json();
            $process = $processData['data'] ?? $processData;
            $processCommand = $process['command'] ?? "Background Process {$backgroundProcessId}";

            $this->warn('You are about to DESTROY the following background process:');
            $this->line("  ID: {$backgroundProcessId}");
            $this->line("  Command: {$processCommand}");
            $this->line("  Organization: {$organization}");
            $this->line("  Server ID: {$serverId}");
            $this->newLine();
            $this->error('This action is IRREVERSIBLE and will permanently delete the background process.');
            $this->newLine();

            if (! $this->confirmOperation("Type 'yes' to confirm you want to destroy background process '{$processCommand}'")) {
                $this->info('Operation cancelled.');

                return self::SUCCESS;
            }
        } catch (Exception $e) {
            $executionTime = round((microtime(true) - $startTime) * 1000, 2);

            $this->logError('Get background process details before destroy', $e->getMessage(), [
                'organization' => $organization,
                'server_id' => $serverId,
                'background_process_id' => $backgroundProcessId,
                'execution_time_ms' => $executionTime,
            ]);

            $this->error("Unable to retrieve background process details: {$e->getMessage()}");
            $this->error('Cannot safely destroy background process without confirming details.');

            return self::FAILURE;
        }

        $this->logOperation('Destroy background process', [
            'organization' => $organization,
            'server_id' => $serverId,
            'background_process_id' => $backgroundProcessId,
        ], 'error'); // Error level for audit trail

        try {
            $response = $forge->backgroundProcesses()->organizationsServersBackgroundProcessesDestroy(
                organization: $organization,
                server: $serverId,
                backgroundProcess: $backgroundProcessId
            );

            if (! $response->successful()) {
                $this->logError('Destroy background process', $response->body(), [
                    'status' => $response->status(),
                    'organization' => $organization,
                    'server_id' => $serverId,
                    'background_process_id' => $backgroundProcessId,
                ]);
                $this->error("Failed to destroy background process: {$response->body()}");

                return self::FAILURE;
            }

            $executionTime = round((microtime(true) - $startTime) * 1000, 2);

            $this->logSuccess('Destroy background process', [
                'organization' => $organization,
                'server_id' => $serverId,
                'background_process_id' => $backgroundProcessId,
                'execution_time_ms' => $executionTime,
            ]);

            $this->info("Background process destroyed successfully in {$executionTime}ms");

            return self::SUCCESS;
        } catch (Exception $e) {
            $executionTime = round((microtime(true) - $startTime) * 1000, 2);

            $this->logError('Destroy background process', $e->getMessage(), [
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
