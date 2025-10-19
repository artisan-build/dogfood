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

class ListSitesCommand extends Command
{
    use HandlesDefaultArguments;
    use LogsForgeOperations;
    use ResolvesResourceIdentifiers;

    protected $signature = 'forge:list-sites
                            {organization? : The organization slug or ID}
                            {server? : The server name or ID}
                            {--sort= : Sort by (name, created_at, etc.)}
                            {--pagesize= : Number of results per page}
                            {--pagecursor= : Cursor for pagination}
                            {--filter-name= : Filter by site name}';

    protected $description = 'List all sites for a server';

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
            $server = $this->resolveServerIdentifier($serverInput, $organization, $forge);
        } catch (InvalidArgumentException $e) {
            $this->error($e->getMessage());

            return self::FAILURE;
        }

        $sort = $this->option('sort');
        $pagesize = $this->option('pagesize') ? (int) $this->option('pagesize') : null;
        $pagecursor = $this->option('pagecursor');
        $filterName = $this->option('filter-name');

        $this->logOperation('List sites', [
            'organization' => $organization,
            'server' => $server,
            'sort' => $sort,
            'pagesize' => $pagesize,
        ]);

        try {
            $response = $forge->sites()->organizationsServersSitesIndex(
                organization: $organization,
                server: $server,
                sort: $sort,
                include: null,
                pagesize: $pagesize,
                pagecursor: $pagecursor,
                filtername: $filterName,
            );

            if (! $response->successful()) {
                $this->logError('List sites', $response->body(), [
                    'status' => $response->status(),
                    'organization' => $organization,
                    'server' => $server,
                ]);
                $this->error("Failed to list sites: {$response->body()}");

                return self::FAILURE;
            }

            $data = $response->json();
            $sites = $data['data'] ?? [];

            $this->table(
                ['ID', 'Name', 'Directory', 'Status', 'Repository', 'Branch', 'PHP', 'Quick Deploy'],
                collect($sites)->map(fn ($site) => [
                    $site['id'] ?? $site['attributes']['id'] ?? 'N/A',
                    $site['attributes']['name'] ?? $site['name'] ?? 'N/A',
                    $site['attributes']['directory'] ?? $site['directory'] ?? 'N/A',
                    $site['attributes']['status'] ?? $site['status'] ?? 'N/A',
                    $site['attributes']['repository'] ?? $site['repository'] ?? 'N/A',
                    $site['attributes']['repository_branch'] ?? $site['repository_branch'] ?? 'N/A',
                    $site['attributes']['php_version'] ?? $site['php_version'] ?? 'N/A',
                    ($site['attributes']['quick_deploy'] ?? $site['quick_deploy'] ?? false) ? 'Yes' : 'No',
                ])->all()
            );

            $executionTime = round((microtime(true) - $startTime) * 1000, 2);

            $this->logSuccess('List sites', [
                'organization' => $organization,
                'server' => $server,
                'count' => count($sites),
                'execution_time_ms' => $executionTime,
            ]);

            $this->info('Listed '.count($sites)." site(s) in {$executionTime}ms");

            return self::SUCCESS;
        } catch (Exception $e) {
            $executionTime = round((microtime(true) - $startTime) * 1000, 2);

            $this->logError('List sites', $e->getMessage(), [
                'organization' => $organization,
                'server' => $server,
                'execution_time_ms' => $executionTime,
            ]);

            $this->error("Error: {$e->getMessage()}");

            return self::FAILURE;
        }
    }
}
