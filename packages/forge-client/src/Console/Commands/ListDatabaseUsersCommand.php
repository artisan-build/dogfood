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

class ListDatabaseUsersCommand extends Command
{
    use HandlesDefaultArguments;
    use LogsForgeOperations;
    use ResolvesResourceIdentifiers;

    protected $signature = 'forge:list-database-users
                            {server? : The server name or ID}
                            {organization? : The organization slug or ID}
                            {--sort= : Sort by (name, created_at, updated_at)}
                            {--pagesize= : Number of results per page}
                            {--pagecursor= : Cursor for pagination}
                            {--filter-name= : Filter by user name}
                            {--filter-status= : Filter by status}';

    protected $description = 'List all database users for a server';

    public function handle(ForgeClient $forge): int
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
        } catch (InvalidArgumentException $e) {
            $this->error($e->getMessage());

            return self::FAILURE;
        }

        $sort = $this->option('sort');
        $pagesize = $this->option('pagesize') ? (int) $this->option('pagesize') : null;
        $pagecursor = $this->option('pagecursor');
        $filterName = $this->option('filter-name');
        $filterStatus = $this->option('filter-status');

        $this->logOperation('List database users', [
            'organization' => $organization,
            'server_id' => $serverId,
            'sort' => $sort,
            'pagesize' => $pagesize,
        ]);

        try {
            $response = $forge->databases()->organizationsServersDatabaseUsersIndex(
                organization: $organization,
                server: $serverId,
                sort: $sort,
                pagesize: $pagesize,
                pagecursor: $pagecursor,
                filtername: $filterName,
                filterstatus: $filterStatus,
            );

            if (! $response->successful()) {
                $this->logError('List database users', $response->body(), [
                    'status' => $response->status(),
                    'organization' => $organization,
                    'server_id' => $serverId,
                ]);
                $this->error("Failed to list database users: {$response->body()}");

                return self::FAILURE;
            }

            $data = $response->json();
            $users = $data['data'] ?? [];

            $this->table(
                ['ID', 'Name', 'Status', 'Created At'],
                collect($users)->map(fn ($user) => [
                    $user['id'] ?? 'N/A',
                    $user['attributes']['name'] ?? $user['name'] ?? 'N/A',
                    $user['attributes']['status'] ?? $user['status'] ?? 'N/A',
                    $user['attributes']['created_at'] ?? $user['created_at'] ?? 'N/A',
                ])->all()
            );

            $executionTime = round((microtime(true) - $startTime) * 1000, 2);

            $this->logSuccess('List database users', [
                'organization' => $organization,
                'server_id' => $serverId,
                'count' => count($users),
                'execution_time_ms' => $executionTime,
            ]);

            $this->info('Listed '.count($users)." database user(s) in {$executionTime}ms");

            return self::SUCCESS;
        } catch (Exception $e) {
            $executionTime = round((microtime(true) - $startTime) * 1000, 2);

            $this->logError('List database users', $e->getMessage(), [
                'organization' => $organization,
                'server_id' => $serverId,
                'execution_time_ms' => $executionTime,
            ]);

            $this->error("Error: {$e->getMessage()}");

            return self::FAILURE;
        }
    }
}
