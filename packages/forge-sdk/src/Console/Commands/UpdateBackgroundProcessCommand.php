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

class UpdateBackgroundProcessCommand extends Command
{
    use HandlesDefaultArguments;
    use LogsForgeOperations;
    use PerformsDestructiveForgeOperations;
    use ResolvesResourceIdentifiers;

    protected $signature = 'forge:update-background-process
                            {organization? : The organization slug or ID}
                            {server? : The server name or ID}
                            {background-process? : The background process ID}
                            {--dangerously-skip-confirmation : Skip confirmation prompt}';

    protected $description = 'Update a background process';

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
        } catch (\InvalidArgumentException $e) {
            $this->error($e->getMessage());

            return self::FAILURE;
        }

        $this->warn('You are about to UPDATE the background process.');
        $this->line("  Organization: {$organization}");
        $this->line("  Server ID: {$serverId}");
        $this->line("  Background Process ID: {$backgroundProcessId}");
        $this->newLine();

        if (! $this->confirmOperation('Do you want to proceed with updating this background process?')) {
            $this->info('Operation cancelled.');

            return self::SUCCESS;
        }

        $this->logOperation('Update background process', [
            'organization' => $organization,
            'server_id' => $serverId,
            'background_process_id' => $backgroundProcessId,
        ], 'warning');

        try {
            $response = $forge->backgroundProcesses()->organizationsServersBackgroundProcessesUpdate(
                organization: $organization,
                server: $serverId,
                backgroundProcess: $backgroundProcessId
            );

            if (! $response->successful()) {
                $this->logError('Update background process', $response->body(), [
                    'status' => $response->status(),
                    'organization' => $organization,
                    'server_id' => $serverId,
                    'background_process_id' => $backgroundProcessId,
                ]);
                $this->error("Failed to update background process: {$response->body()}");

                return self::FAILURE;
            }

            $data = $response->json();
            $process = $data['data'] ?? $data;

            $executionTime = round((microtime(true) - $startTime) * 1000, 2);

            $this->logSuccess('Update background process', [
                'organization' => $organization,
                'server_id' => $serverId,
                'background_process_id' => $backgroundProcessId,
                'execution_time_ms' => $executionTime,
            ]);

            $this->info("Background process updated successfully in {$executionTime}ms");
            $this->table(
                ['Property', 'Value'],
                [
                    ['ID', $process['id'] ?? 'N/A'],
                    ['Command', $process['command'] ?? 'N/A'],
                    ['Status', $process['status'] ?? 'N/A'],
                ]
            );

            return self::SUCCESS;
        } catch (Exception $e) {
            $executionTime = round((microtime(true) - $startTime) * 1000, 2);

            $this->logError('Update background process', $e->getMessage(), [
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
