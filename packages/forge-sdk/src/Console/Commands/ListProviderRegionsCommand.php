<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeSdk\Console\Commands;

use ArtisanBuild\ForgeSdk\Console\Concerns\LogsForgeOperations;
use ArtisanBuild\ForgeSdk\ForgeSdk;
use Exception;
use Illuminate\Console\Command;

class ListProviderRegionsCommand extends Command
{
    use LogsForgeOperations;

    protected $signature = 'forge:list-provider-regions
                            {provider : The provider ID}
                            {--pagesize= : Number of results per page}
                            {--pagecursor= : Cursor for pagination}';

    protected $description = 'List available regions for a provider';

    public function handle(ForgeSdk $forge): int
    {
        $startTime = microtime(true);

        $provider = (int) $this->argument('provider');
        $pagesize = $this->option('pagesize') ? (int) $this->option('pagesize') : null;
        $pagecursor = $this->option('pagecursor');

        $this->logOperation('List provider regions', [
            'provider' => $provider,
            'pagesize' => $pagesize,
            'pagecursor' => $pagecursor,
        ]);

        try {
            $response = $forge->providers()->providersRegionsIndex($provider, $pagesize, $pagecursor);

            if (! $response->successful()) {
                $this->logError('List provider regions', $response->body(), [
                    'status' => $response->status(),
                    'provider' => $provider,
                ]);
                $this->error("Failed to list provider regions: {$response->body()}");

                return self::FAILURE;
            }

            $data = $response->json();
            $regions = $data['data'] ?? [];

            if (empty($regions)) {
                $this->info('No regions found for this provider.');

                return self::SUCCESS;
            }

            $this->table(
                ['ID', 'Name', 'Code', 'Alternate Code'],
                collect($regions)->map(function ($region) {
                    $attributes = $region['attributes'] ?? $region;

                    return [
                        $region['id'] ?? 'N/A',
                        $attributes['name'] ?? 'N/A',
                        $attributes['code'] ?? 'N/A',
                        $attributes['alternate_code'] ?? 'N/A',
                    ];
                })->all()
            );

            $executionTime = round((microtime(true) - $startTime) * 1000, 2);

            $this->logSuccess('List provider regions', [
                'provider' => $provider,
                'count' => count($regions),
                'execution_time_ms' => $executionTime,
            ]);

            $this->info('Listed '.count($regions)." region(s) for provider {$provider} in {$executionTime}ms");

            return self::SUCCESS;
        } catch (Exception $e) {
            $executionTime = round((microtime(true) - $startTime) * 1000, 2);

            $this->logError('List provider regions', $e->getMessage(), [
                'provider' => $provider,
                'execution_time_ms' => $executionTime,
            ]);

            $this->error("Error: {$e->getMessage()}");

            return self::FAILURE;
        }
    }
}
