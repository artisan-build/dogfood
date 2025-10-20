<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeClient\Console\Commands;

use ArtisanBuild\ForgeClient\Console\Concerns\LogsForgeOperations;
use ArtisanBuild\ForgeClient\Console\Concerns\ResolvesResourceIdentifiers;
use ArtisanBuild\ForgeClient\ForgeClient;
use Exception;
use Illuminate\Console\Command;
use InvalidArgumentException;

class GetOrganizationCommand extends Command
{
    use LogsForgeOperations;
    use ResolvesResourceIdentifiers;

    protected $signature = 'forge:get-organization {organization : The organization slug or ID}';

    protected $description = 'Get details for a specific Laravel Forge organization';

    public function handle(ForgeClient $forge): int
    {
        $startTime = microtime(true);

        $organizationInput = $this->argument('organization');

        try {
            $organization = $this->resolveOrganizationSlug($organizationInput, $forge);
        } catch (InvalidArgumentException $e) {
            $this->error($e->getMessage());

            return self::FAILURE;
        }

        $this->logOperation('Get organization', [
            'organization' => $organization,
        ]);

        try {
            $response = $forge->organizations()->organizationsShow($organization);

            if (! $response->successful()) {
                $this->logError('Get organization', $response->body(), [
                    'status' => $response->status(),
                    'organization' => $organization,
                ]);
                $this->error("Failed to get organization: {$response->body()}");

                return self::FAILURE;
            }

            $data = $response->json();
            $org = $data['data'] ?? $data;

            $this->info("Organization: {$org['name']}");
            $this->line("Slug: {$org['slug']}");
            $this->line("ID: {$org['id']}");

            if (isset($org['created_at'])) {
                $this->line("Created: {$org['created_at']}");
            }

            $executionTime = round((microtime(true) - $startTime) * 1000, 2);

            $this->logSuccess('Get organization', [
                'organization' => $organization,
                'execution_time_ms' => $executionTime,
            ]);

            $this->newLine();
            $this->comment("Retrieved in {$executionTime}ms");

            return self::SUCCESS;
        } catch (Exception $e) {
            $executionTime = round((microtime(true) - $startTime) * 1000, 2);

            $this->logError('Get organization', $e->getMessage(), [
                'organization' => $organization,
                'execution_time_ms' => $executionTime,
            ]);

            $this->error("Error: {$e->getMessage()}");

            return self::FAILURE;
        }
    }
}
