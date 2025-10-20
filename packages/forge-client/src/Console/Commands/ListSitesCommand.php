<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeClient\Console\Commands;

use ArtisanBuild\ForgeClient\Console\Concerns\HandlesDefaultArguments;
use ArtisanBuild\ForgeClient\Console\Concerns\LogsForgeOperations;
use ArtisanBuild\ForgeClient\Console\Concerns\ResolvesResourceIdentifiers;
use ArtisanBuild\ForgeClient\ForgeClient;
use Exception;
use Illuminate\Console\Command;
use InvalidArgumentException;

class ListSitesCommand extends Command
{
    use HandlesDefaultArguments;
    use LogsForgeOperations;
    use ResolvesResourceIdentifiers;

    protected $signature = 'forge:list-sites
                            {server? : The server name or ID}
                            {organization? : The organization slug or ID}
                            {--sort= : Sort by (name, created_at, etc.)}
                            {--pagesize= : Number of results per page}
                            {--pagecursor= : Cursor for pagination}
                            {--filter-name= : Filter by site name}';

    protected $description = 'List all sites for a server';

    public function handle(ForgeClient $forge): int
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

            if (empty($sites)) {
                $this->info('No sites found.');

                return self::SUCCESS;
            }

            foreach ($sites as $site) {
                $id = $site['id'] ?? 'N/A';
                $attrs = $site['attributes'] ?? [];

                // Extract repository information
                $repository = $attrs['repository'] ?? null;
                $repositoryUrl = is_array($repository) ? ($repository['url'] ?? null) : $repository;
                $repositoryBranch = is_array($repository) ? ($repository['branch'] ?? null) : null;

                $this->newLine();
                $this->line("<fg=cyan>Site #{$id}</>");
                $this->line("  <fg=gray>Domain:</> {$attrs['name']}");

                // Show URL if available
                if (isset($attrs['url'])) {
                    $this->line("  <fg=gray>URL:</> {$attrs['url']}");
                }

                // Show SSL/HTTPS status
                $httpsStatus = ($attrs['https'] ?? false)
                    ? '<fg=green>SSL Enabled</>'
                    : '<fg=yellow>No SSL</>';
                $this->line("  <fg=gray>SSL:</> {$httpsStatus}");

                // Show aliases if any
                if (! empty($attrs['aliases'])) {
                    $aliases = is_array($attrs['aliases'])
                        ? implode(', ', $attrs['aliases'])
                        : $attrs['aliases'];
                    $this->line("  <fg=gray>Aliases:</> {$aliases}");
                }

                if ($repositoryUrl) {
                    $this->line("  <fg=gray>Repository:</> {$repositoryUrl}");
                    if ($repositoryBranch) {
                        $this->line("  <fg=gray>Branch:</> {$repositoryBranch}");
                    }
                }

                $this->line("  <fg=gray>PHP:</> {$attrs['php_version']}");
                $this->line("  <fg=gray>Directory:</> {$attrs['web_directory']}");

                $deploymentStatus = $attrs['deployment_status'] ?? null;
                if ($deploymentStatus) {
                    $this->line("  <fg=gray>Deployment:</> {$deploymentStatus}");
                }

                $quickDeploy = ($attrs['quick_deploy'] ?? false) ? '<fg=green>Enabled</>' : '<fg=red>Disabled</>';
                $this->line("  <fg=gray>Quick Deploy:</> {$quickDeploy}");
            }

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
