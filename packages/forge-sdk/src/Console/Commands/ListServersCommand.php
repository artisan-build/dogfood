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

class ListServersCommand extends Command
{
    use HandlesDefaultArguments;
    use LogsForgeOperations;
    use ResolvesResourceIdentifiers;

    protected $signature = 'forge:list-servers
                            {organization? : The organization slug or ID}
                            {--sort= : Sort by (name, provider, region, created_at, etc.)}
                            {--pagesize= : Number of results per page}
                            {--pagecursor= : Cursor for pagination}
                            {--filter-name= : Filter by server name}
                            {--filter-region= : Filter by region}
                            {--filter-provider= : Filter by provider}';

    protected $description = 'List all servers for an organization';

    public function handle(ForgeSdk $forge): int
    {
        $startTime = microtime(true);

        $organizationInput = $this->getOrganizationArgument();

        if (! $organizationInput) {
            $this->error('Organization is required. Either pass the organization argument or set FORGE_ORGANIZATION in your environment.');

            return self::FAILURE;
        }

        try {
            $organization = $this->resolveOrganizationSlug($organizationInput, $forge);
        } catch (InvalidArgumentException $e) {
            $this->error($e->getMessage());

            return self::FAILURE;
        }
        $sort = $this->option('sort');
        $pagesize = $this->option('pagesize') ? (int) $this->option('pagesize') : null;
        $pagecursor = $this->option('pagecursor');
        $filterName = $this->option('filter-name');
        $filterRegion = $this->option('filter-region');
        $filterProvider = $this->option('filter-provider');

        $this->logOperation('List servers', [
            'organization' => $organization,
            'sort' => $sort,
            'pagesize' => $pagesize,
        ]);

        try {
            $response = $forge->servers()->organizationsServersIndex(
                organization: $organization,
                sort: $sort,
                pagesize: $pagesize,
                pagecursor: $pagecursor,
                filteripAddress: null,
                filtername: $filterName,
                filterregion: $filterRegion,
                filtersize: null,
                filterprovider: $filterProvider,
                filterubuntuVersion: null,
                filterphpVersion: null,
                filterdatabaseType: null,
            );

            if (! $response->successful()) {
                $this->logError('List servers', $response->body(), [
                    'status' => $response->status(),
                    'organization' => $organization,
                ]);
                $this->error("Failed to list servers: {$response->body()}");

                return self::FAILURE;
            }

            $data = $response->json();
            $servers = $data['data'] ?? [];

            $this->table(
                ['ID', 'Name', 'Provider', 'Region', 'PHP', 'IP Address', 'Status'],
                collect($servers)->map(fn ($server) => [
                    $server['id'] ?? 'N/A',
                    $server['attributes']['name'] ?? 'N/A',
                    $server['attributes']['provider'] ?? 'N/A',
                    $server['attributes']['region'] ?? 'N/A',
                    $server['attributes']['php_version'] ?? 'N/A',
                    $server['attributes']['ip_address'] ?? 'N/A',
                    $server['attributes']['connection_status'] ?? 'N/A',
                ])->all()
            );

            $executionTime = round((microtime(true) - $startTime) * 1000, 2);

            $this->logSuccess('List servers', [
                'organization' => $organization,
                'count' => count($servers),
                'execution_time_ms' => $executionTime,
            ]);

            $this->info('Listed '.count($servers)." server(s) in {$executionTime}ms");

            return self::SUCCESS;
        } catch (Exception $e) {
            $executionTime = round((microtime(true) - $startTime) * 1000, 2);

            $this->logError('List servers', $e->getMessage(), [
                'organization' => $organization,
                'execution_time_ms' => $executionTime,
            ]);

            $this->error("Error: {$e->getMessage()}");

            return self::FAILURE;
        }
    }
}
