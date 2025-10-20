<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeSdk\Console\Commands;

use ArtisanBuild\ForgeSdk\Console\Concerns\LogsForgeOperations;
use ArtisanBuild\ForgeSdk\ForgeSdk;
use Exception;
use Illuminate\Console\Command;

class ListProviderSizesCommand extends Command
{
    use LogsForgeOperations;

    protected $signature = 'forge:list-provider-sizes
                            {provider : The provider ID}
                            {--pagesize= : Number of results per page}
                            {--pagecursor= : Cursor for pagination}';

    protected $description = 'List available server sizes for a Laravel Forge provider';

    public function handle(ForgeSdk $forge): int
    {
        $startTime = microtime(true);

        $provider = (int) $this->argument('provider');
        $pagesize = $this->option('pagesize') ? (int) $this->option('pagesize') : null;
        $pagecursor = $this->option('pagecursor');

        $this->logOperation('List provider sizes', [
            'provider' => $provider,
            'pagesize' => $pagesize,
            'pagecursor' => $pagecursor,
        ]);

        try {
            $response = $forge->providers()->providersSizesIndex($provider, $pagesize, $pagecursor);

            if (! $response->successful()) {
                $this->logError('List provider sizes', $response->body(), [
                    'status' => $response->status(),
                    'provider' => $provider,
                ]);
                $this->error("Failed to list provider sizes: {$response->body()}");

                return self::FAILURE;
            }

            $data = $response->json();
            $sizes = $data['data'] ?? [];

            $this->table(
                ['ID', 'Name', 'RAM (MB)', 'CPUs', 'Disk (GB)', 'Architecture'],
                collect($sizes)->map(function ($size) {
                    $attributes = $size['attributes'] ?? $size;

                    return [
                        $size['id'] ?? 'N/A',
                        $attributes['name'] ?? 'N/A',
                        $attributes['ram'] ?? 'N/A',
                        $attributes['cpus'] ?? 'N/A',
                        isset($attributes['disk']) ? round($attributes['disk'] / 1024, 1) : 'N/A',
                        $attributes['architecture'] ?? 'N/A',
                    ];
                })->all()
            );

            $this->newLine();
            $this->info('Note: Use the size ID when creating servers with --size=[ID]');

            $executionTime = round((microtime(true) - $startTime) * 1000, 2);

            $this->logSuccess('List provider sizes', [
                'provider' => $provider,
                'count' => count($sizes),
                'execution_time_ms' => $executionTime,
            ]);

            $this->info('Listed '.count($sizes)." size(s) for provider {$provider} in {$executionTime}ms");

            return self::SUCCESS;
        } catch (Exception $e) {
            $executionTime = round((microtime(true) - $startTime) * 1000, 2);

            $this->logError('List provider sizes', $e->getMessage(), [
                'provider' => $provider,
                'execution_time_ms' => $executionTime,
            ]);

            $this->error("Error: {$e->getMessage()}");

            return self::FAILURE;
        }
    }
}
