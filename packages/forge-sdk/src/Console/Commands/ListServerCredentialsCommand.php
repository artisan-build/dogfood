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

class ListServerCredentialsCommand extends Command
{
    use HandlesDefaultArguments;
    use LogsForgeOperations;
    use ResolvesResourceIdentifiers;

    protected $signature = 'forge:list-server-credentials
                            {organization? : The organization slug or ID}
                            {--pagesize= : Number of results per page}
                            {--pagecursor= : Cursor for pagination}';

    protected $description = 'List server credentials for an organization';

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

        $pagesize = $this->option('pagesize') ? (int) $this->option('pagesize') : null;
        $pagecursor = $this->option('pagecursor');

        $this->logOperation('List server credentials', [
            'organization' => $organization,
            'pagesize' => $pagesize,
            'pagecursor' => $pagecursor,
        ]);

        try {
            $response = $forge->organizations()->organizationsServerCredentialsIndex($organization, $pagesize, $pagecursor);

            if (! $response->successful()) {
                $this->logError('List server credentials', $response->body(), [
                    'status' => $response->status(),
                    'organization' => $organization,
                ]);
                $this->error("Failed to list server credentials: {$response->body()}");

                return self::FAILURE;
            }

            $data = $response->json();
            $credentials = $data['data'] ?? [];

            $this->table(
                ['ID', 'Name', 'Provider'],
                collect($credentials)->map(function ($credential) {
                    $attributes = $credential['attributes'] ?? $credential;

                    return [
                        $credential['id'] ?? 'N/A',
                        $attributes['name'] ?? 'N/A',
                        $attributes['provider'] ?? 'N/A',
                    ];
                })->all()
            );

            $executionTime = round((microtime(true) - $startTime) * 1000, 2);

            $this->logSuccess('List server credentials', [
                'organization' => $organization,
                'count' => count($credentials),
                'execution_time_ms' => $executionTime,
            ]);

            $this->info('Listed '.count($credentials)." credential(s) for organization {$organization} in {$executionTime}ms");

            return self::SUCCESS;
        } catch (Exception $e) {
            $executionTime = round((microtime(true) - $startTime) * 1000, 2);

            $this->logError('List server credentials', $e->getMessage(), [
                'organization' => $organization,
                'execution_time_ms' => $executionTime,
            ]);

            $this->error("Error: {$e->getMessage()}");

            return self::FAILURE;
        }
    }
}
