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

class GetServerCommand extends Command
{
    use HandlesDefaultArguments;
    use LogsForgeOperations;
    use ResolvesResourceIdentifiers;

    protected $signature = 'forge:get-server
                            {organization? : The organization slug or ID}
                            {server? : The server name or ID}';

    protected $description = 'Get details for a specific server';

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

        $this->logOperation('Get server', [
            'organization' => $organization,
            'server_id' => $serverId,
        ]);

        try {
            $response = $forge->servers()->organizationsServersShow($organization, $serverId);

            if (! $response->successful()) {
                $this->logError('Get server', $response->body(), [
                    'status' => $response->status(),
                    'organization' => $organization,
                    'server_id' => $serverId,
                ]);
                $this->error("Failed to get server: {$response->body()}");

                return self::FAILURE;
            }

            $data = $response->json();
            $server = $data['data'] ?? $data;

            $this->info("Server: {$server['name']}");
            $this->line("ID: {$server['id']}");
            $this->line("Provider: {$server['provider']}");
            $this->line("Region: {$server['region']}");
            $this->line("Size: {$server['size']}");
            $this->line("IP Address: {$server['ip_address']}");
            $this->line("PHP Version: {$server['php_version']}");
            $this->line("Ubuntu Version: {$server['ubuntu_version']}");
            $this->line("Status: {$server['status']}");

            if (isset($server['database_type'])) {
                $this->line("Database: {$server['database_type']}");
            }

            if (isset($server['created_at'])) {
                $this->line("Created: {$server['created_at']}");
            }

            $executionTime = round((microtime(true) - $startTime) * 1000, 2);

            $this->logSuccess('Get server', [
                'organization' => $organization,
                'server_id' => $serverId,
                'execution_time_ms' => $executionTime,
            ]);

            $this->newLine();
            $this->comment("Retrieved in {$executionTime}ms");

            return self::SUCCESS;
        } catch (Exception $e) {
            $executionTime = round((microtime(true) - $startTime) * 1000, 2);

            $this->logError('Get server', $e->getMessage(), [
                'organization' => $organization,
                'server_id' => $serverId,
                'execution_time_ms' => $executionTime,
            ]);

            $this->error("Error: {$e->getMessage()}");

            return self::FAILURE;
        }
    }
}
