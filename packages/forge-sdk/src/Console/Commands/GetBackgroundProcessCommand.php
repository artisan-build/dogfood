<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeSdk\Console\Commands;

use ArtisanBuild\ForgeSdk\Console\Concerns\HandlesDefaultArguments;
use ArtisanBuild\ForgeSdk\Console\Concerns\LogsForgeOperations;
use ArtisanBuild\ForgeSdk\Console\Concerns\ResolvesResourceIdentifiers;
use ArtisanBuild\ForgeSdk\ForgeSdk;
use Exception;
use Illuminate\Console\Command;

class GetBackgroundProcessCommand extends Command
{
    use HandlesDefaultArguments;
    use LogsForgeOperations;
    use ResolvesResourceIdentifiers;

    protected $signature = 'forge:get-background-process
                            {organization? : The organization slug or ID}
                            {server? : The server name or ID}
                            {background-process? : The background process ID}';

    protected $description = 'Get details for a specific background process';

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

        $this->logOperation('Get background process', [
            'organization' => $organization,
            'server_id' => $serverId,
            'background_process_id' => $backgroundProcessId,
        ]);

        try {
            $response = $forge->backgroundProcesses()->organizationsServersBackgroundProcessesShow(
                organization: $organization,
                server: $serverId,
                backgroundProcess: $backgroundProcessId
            );

            if (! $response->successful()) {
                $this->logError('Get background process', $response->body(), [
                    'status' => $response->status(),
                    'organization' => $organization,
                    'server_id' => $serverId,
                    'background_process_id' => $backgroundProcessId,
                ]);
                $this->error("Failed to get background process: {$response->body()}");

                return self::FAILURE;
            }

            $data = $response->json();
            $process = $data['data'] ?? $data;

            $this->table(
                ['Property', 'Value'],
                [
                    ['ID', $process['id'] ?? 'N/A'],
                    ['Command', $process['command'] ?? 'N/A'],
                    ['Status', $process['status'] ?? 'N/A'],
                    ['Created At', $process['created_at'] ?? 'N/A'],
                    ['Updated At', $process['updated_at'] ?? 'N/A'],
                ]
            );

            $executionTime = round((microtime(true) - $startTime) * 1000, 2);

            $this->logSuccess('Get background process', [
                'organization' => $organization,
                'server_id' => $serverId,
                'background_process_id' => $backgroundProcessId,
                'execution_time_ms' => $executionTime,
            ]);

            $this->info("Retrieved background process details in {$executionTime}ms");

            return self::SUCCESS;
        } catch (Exception $e) {
            $executionTime = round((microtime(true) - $startTime) * 1000, 2);

            $this->logError('Get background process', $e->getMessage(), [
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
