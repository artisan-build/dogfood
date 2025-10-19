<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeSdk\Console\Commands;

use ArtisanBuild\ForgeSdk\Console\Concerns\HandlesDefaultArguments;
use ArtisanBuild\ForgeSdk\Console\Concerns\LogsForgeOperations;
use ArtisanBuild\ForgeSdk\Console\Concerns\ResolvesResourceIdentifiers;
use ArtisanBuild\ForgeSdk\ForgeSdk;
use Exception;
use Illuminate\Console\Command;

class ListBackgroundProcessesCommand extends Command
{
    use HandlesDefaultArguments;
    use LogsForgeOperations;
    use ResolvesResourceIdentifiers;

    protected $signature = 'forge:list-background-processes
                            {organization? : The organization slug or ID}
                            {server? : The server name or ID}
                            {--sort= : Sort by (user)}
                            {--pagesize= : Number of results per page}
                            {--pagecursor= : Cursor for pagination}
                            {--filter-user= : Filter by user}
                            {--filter-site-id= : Filter by site ID}
                            {--filter-directory= : Filter by directory}';

    protected $description = 'List all background processes for a server';

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

        try {
            $organization = $this->resolveOrganizationSlug($organizationInput, $forge);
            $serverId = $this->resolveServerId($serverInput, $organization, $forge);
        } catch (\InvalidArgumentException $e) {
            $this->error($e->getMessage());

            return self::FAILURE;
        }

        $sort = $this->option('sort');
        $pagesize = $this->option('pagesize') ? (int) $this->option('pagesize') : null;
        $pagecursor = $this->option('pagecursor');
        $filterUser = $this->option('filter-user');
        $filterSiteId = $this->option('filter-site-id');
        $filterDirectory = $this->option('filter-directory');

        $this->logOperation('List background processes', [
            'organization' => $organization,
            'server_id' => $serverId,
            'sort' => $sort,
            'pagesize' => $pagesize,
        ]);

        try {
            $response = $forge->backgroundProcesses()->organizationsServersBackgroundProcessesIndex(
                organization: $organization,
                server: $serverId,
                sort: $sort,
                pagesize: $pagesize,
                pagecursor: $pagecursor,
                filteruser: $filterUser,
                filtersiteId: $filterSiteId,
                filterdirectory: $filterDirectory,
            );

            if (! $response->successful()) {
                $this->logError('List background processes', $response->body(), [
                    'status' => $response->status(),
                    'organization' => $organization,
                    'server_id' => $serverId,
                ]);
                $this->error("Failed to list background processes: {$response->body()}");

                return self::FAILURE;
            }

            $data = $response->json();
            $processes = $data['data'] ?? [];

            $this->table(
                ['ID', 'User', 'Command', 'Directory', 'Status'],
                collect($processes)->map(fn ($process) => [
                    $process['id'] ?? 'N/A',
                    $process['user'] ?? 'N/A',
                    str($process['command'] ?? 'N/A')->limit(50),
                    str($process['directory'] ?? 'N/A')->limit(30),
                    $process['status'] ?? 'N/A',
                ])->all()
            );

            $executionTime = round((microtime(true) - $startTime) * 1000, 2);

            $this->logSuccess('List background processes', [
                'organization' => $organization,
                'server_id' => $serverId,
                'count' => count($processes),
                'execution_time_ms' => $executionTime,
            ]);

            $this->info('Listed '.count($processes)." background process(es) in {$executionTime}ms");

            return self::SUCCESS;
        } catch (Exception $e) {
            $executionTime = round((microtime(true) - $startTime) * 1000, 2);

            $this->logError('List background processes', $e->getMessage(), [
                'organization' => $organization,
                'server_id' => $serverId,
                'execution_time_ms' => $executionTime,
            ]);

            $this->error("Error: {$e->getMessage()}");

            return self::FAILURE;
        }
    }
}
