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

class ListDeploymentsCommand extends Command
{
    use HandlesDefaultArguments;
    use LogsForgeOperations;
    use ResolvesResourceIdentifiers;

    protected $signature = 'forge:list-deployments
                            {organization? : The organization slug or ID}
                            {server? : The server name or ID}
                            {site? : The site name or ID}
                            {--sort= : Sort by (created_at, etc.)}
                            {--pagesize= : Number of results per page}
                            {--pagecursor= : Cursor for pagination}';

    protected $description = 'List deployment history for a site';

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
        $siteInput = $this->argument('site');

        try {
            $organization = $this->resolveOrganizationSlug($organizationInput, $forge);
            $server = $this->resolveServerIdentifier($serverInput, $organization, $forge);
            $site = $this->resolveSiteIdentifier($siteInput, $organization, $server, $forge);
        } catch (InvalidArgumentException $e) {
            $this->error($e->getMessage());

            return self::FAILURE;
        }

        $sort = $this->option('sort');
        $pagesize = $this->option('pagesize') ? (int) $this->option('pagesize') : null;
        $pagecursor = $this->option('pagecursor');

        $this->logOperation('List deployments', [
            'organization' => $organization,
            'server' => $server,
            'site' => $site,
            'sort' => $sort,
            'pagesize' => $pagesize,
        ]);

        try {
            $response = $forge->deployments()->organizationsServersSitesDeploymentsIndex(
                organization: $organization,
                server: $server,
                site: $site,
                sort: $sort,
                pagesize: $pagesize,
                pagecursor: $pagecursor,
                filtercommitHash: null,
                filtercommitMessage: null,
                filtercommitAuthor: null,
            );

            if (! $response->successful()) {
                $this->logError('List deployments', $response->body(), [
                    'status' => $response->status(),
                    'organization' => $organization,
                    'server' => $server,
                    'site' => $site,
                ]);
                $this->error("Failed to list deployments: {$response->body()}");

                return self::FAILURE;
            }

            $data = $response->json();
            $deployments = $data['data'] ?? [];

            $this->table(
                ['ID', 'Status', 'Commit Hash', 'Author', 'Message', 'Started', 'Duration (s)'],
                collect($deployments)->map(fn ($deployment) => [
                    $deployment['id'] ?? 'N/A',
                    $deployment['status'] ?? 'N/A',
                    substr($deployment['commit_hash'] ?? 'N/A', 0, 8),
                    $deployment['commit_author'] ?? 'N/A',
                    strlen($deployment['commit_message'] ?? '') > 30
                        ? substr((string) $deployment['commit_message'], 0, 27).'...'
                        : ($deployment['commit_message'] ?? 'N/A'),
                    $deployment['started_at'] ?? 'N/A',
                    $deployment['duration'] ?? 'N/A',
                ])->all()
            );

            $executionTime = round((microtime(true) - $startTime) * 1000, 2);

            $this->logSuccess('List deployments', [
                'organization' => $organization,
                'server' => $server,
                'site' => $site,
                'count' => count($deployments),
                'execution_time_ms' => $executionTime,
            ]);

            $this->info('Listed '.count($deployments)." deployment(s) in {$executionTime}ms");

            return self::SUCCESS;
        } catch (Exception $e) {
            $executionTime = round((microtime(true) - $startTime) * 1000, 2);

            $this->logError('List deployments', $e->getMessage(), [
                'organization' => $organization,
                'server' => $server,
                'site' => $site,
                'execution_time_ms' => $executionTime,
            ]);

            $this->error("Error: {$e->getMessage()}");

            return self::FAILURE;
        }
    }
}
