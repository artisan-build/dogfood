<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeClient\Console\Commands;

use ArtisanBuild\ForgeClient\Console\Concerns\LogsForgeOperations;
use ArtisanBuild\ForgeClient\ForgeClient;
use Exception;
use Illuminate\Console\Command;

class ListOrganizationsCommand extends Command
{
    use LogsForgeOperations;

    protected $signature = 'forge:list-organizations
                            {--pagesize= : Number of results per page}
                            {--pagecursor= : Cursor for pagination}';

    protected $description = 'List all Laravel Forge organizations';

    public function handle(ForgeClient $forge): int
    {
        $startTime = microtime(true);

        $pagesize = $this->option('pagesize') ? (int) $this->option('pagesize') : null;
        $pagecursor = $this->option('pagecursor');

        $this->logOperation('List organizations', [
            'pagesize' => $pagesize,
            'pagecursor' => $pagecursor,
        ]);

        try {
            $response = $forge->organizations()->organizationsIndex($pagesize, $pagecursor);

            if (! $response->successful()) {
                $this->logError('List organizations', $response->body(), [
                    'status' => $response->status(),
                ]);
                $this->error("Failed to list organizations: {$response->body()}");

                return self::FAILURE;
            }

            $data = $response->json();
            $organizations = $data['data'] ?? [];

            $this->table(
                ['ID', 'Name', 'Slug', 'Created At'],
                collect($organizations)->map(fn ($org) => [
                    $org['id'] ?? 'N/A',
                    $org['attributes']['name'] ?? $org['name'] ?? 'N/A',
                    $org['attributes']['slug'] ?? $org['slug'] ?? 'N/A',
                    $org['attributes']['created_at'] ?? $org['created_at'] ?? 'N/A',
                ])->all()
            );

            $executionTime = round((microtime(true) - $startTime) * 1000, 2);

            $this->logSuccess('List organizations', [
                'count' => count($organizations),
                'execution_time_ms' => $executionTime,
            ]);

            $this->info('Listed '.count($organizations)." organization(s) in {$executionTime}ms");

            return self::SUCCESS;
        } catch (Exception $e) {
            $executionTime = round((microtime(true) - $startTime) * 1000, 2);

            $this->logError('List organizations', $e->getMessage(), [
                'execution_time_ms' => $executionTime,
            ]);

            $this->error("Error: {$e->getMessage()}");

            return self::FAILURE;
        }
    }
}
