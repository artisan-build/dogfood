<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeSdk\Console\Commands;

use ArtisanBuild\ForgeSdk\Console\Concerns\LogsForgeOperations;
use ArtisanBuild\ForgeSdk\ForgeSdk;
use Exception;
use Illuminate\Console\Command;

class ListProvidersCommand extends Command
{
    use LogsForgeOperations;

    protected $signature = 'forge:list-providers
                            {--pagesize= : Number of results per page}
                            {--pagecursor= : Cursor for pagination}';

    protected $description = 'List all available cloud providers in Laravel Forge';

    public function handle(ForgeSdk $forge): int
    {
        $startTime = microtime(true);

        $pagesize = $this->option('pagesize') ? (int) $this->option('pagesize') : null;
        $pagecursor = $this->option('pagecursor');

        $this->logOperation('List providers', [
            'pagesize' => $pagesize,
            'pagecursor' => $pagecursor,
        ]);

        try {
            $response = $forge->providers()->providersIndex($pagesize, $pagecursor);

            if (! $response->successful()) {
                $this->logError('List providers', $response->body(), [
                    'status' => $response->status(),
                ]);
                $this->error("Failed to list providers: {$response->body()}");

                return self::FAILURE;
            }

            $data = $response->json();
            $providers = $data['data'] ?? [];

            $this->table(
                ['ID', 'Name', 'Type'],
                collect($providers)->map(fn ($provider) => [
                    $provider['id'] ?? 'N/A',
                    $provider['attributes']['name'] ?? $provider['name'] ?? 'N/A',
                    $provider['attributes']['type'] ?? $provider['type'] ?? 'N/A',
                ])->all()
            );

            $executionTime = round((microtime(true) - $startTime) * 1000, 2);

            $this->logSuccess('List providers', [
                'count' => count($providers),
                'execution_time_ms' => $executionTime,
            ]);

            $this->info('Listed '.count($providers)." provider(s) in {$executionTime}ms");

            return self::SUCCESS;
        } catch (Exception $e) {
            $executionTime = round((microtime(true) - $startTime) * 1000, 2);

            $this->logError('List providers', $e->getMessage(), [
                'execution_time_ms' => $executionTime,
            ]);

            $this->error("Error: {$e->getMessage()}");

            return self::FAILURE;
        }
    }
}
