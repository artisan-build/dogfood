<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeClient\Console\Commands;

use ArtisanBuild\ForgeClient\Console\Concerns\HandlesDefaultArguments;
use ArtisanBuild\ForgeClient\Console\Concerns\LogsForgeOperations;
use ArtisanBuild\ForgeClient\Console\Concerns\PerformsDestructiveForgeOperations;
use ArtisanBuild\ForgeClient\Console\Concerns\ResolvesResourceIdentifiers;
use ArtisanBuild\ForgeClient\ForgeClient;
use Exception;
use Illuminate\Console\Command;
use InvalidArgumentException;

class UpdateEnvironmentCommand extends Command
{
    use HandlesDefaultArguments;
    use LogsForgeOperations;
    use PerformsDestructiveForgeOperations;
    use ResolvesResourceIdentifiers;

    protected $signature = 'forge:update-environment
                            {site? : The site name or ID}
                            {server? : The server name or ID}
                            {organization? : The organization slug or ID}
                            {--environment= : The environment file content (can also pipe via stdin)}
                            {--file= : Path to a file containing the environment content}
                            {--cache : Restart cache after updating}
                            {--queues : Restart queues after updating}
                            {--encryption-key= : Set the APP_KEY encryption key}
                            {--dangerously-skip-confirmation : Skip confirmation prompt}';

    protected $description = 'Update the environment file (.env) content for a site';

    public function handle(ForgeClient $forge): int
    {
        $startTime = microtime(true);

        $organizationInput = $this->getOrganizationArgument();
        $serverInput = $this->getServerArgument();
        $siteInput = $this->argument('site');

        if (! $organizationInput) {
            $this->error('Organization is required. Either pass the organization argument or set FORGE_ORGANIZATION in your environment.');

            return self::FAILURE;
        }

        if (! $serverInput) {
            $this->error('Server is required. Either pass the server argument or set FORGE_SERVER in your environment.');

            return self::FAILURE;
        }

        if (! $siteInput) {
            $this->error('Site is required.');

            return self::FAILURE;
        }

        try {
            $organization = $this->resolveOrganizationSlug($organizationInput, $forge);
            $serverId = $this->resolveServerId($serverInput, $organization, $forge);
            $siteId = $this->resolveSiteIdentifier($siteInput, $organization, $serverId, $forge);
        } catch (InvalidArgumentException $e) {
            $this->error($e->getMessage());

            return self::FAILURE;
        }

        // Get environment content from option, file, or stdin
        $environment = $this->option('environment');

        if (! $environment && $this->option('file')) {
            $filePath = $this->option('file');
            if (! file_exists($filePath)) {
                $this->error("File not found: {$filePath}");

                return self::FAILURE;
            }
            $environment = file_get_contents($filePath);
        }

        if (! $environment) {
            // Check if stdin has content
            $stdin = '';
            stream_set_blocking(STDIN, false);
            while ($line = fgets(STDIN)) {
                $stdin .= $line;
            }
            stream_set_blocking(STDIN, true);

            if ($stdin) {
                $environment = $stdin;
            }
        }

        if (! $environment) {
            $this->error('Environment content is required. Use --environment, --file, or pipe content via stdin.');

            return self::FAILURE;
        }

        $this->warn('You are about to UPDATE the environment file for this site.');
        $this->line("  Organization: {$organization}");
        $this->line("  Server ID: {$serverId}");
        $this->line("  Site ID: {$siteId}");
        if ($this->option('cache')) {
            $this->line('  Restart cache: Yes');
        }
        if ($this->option('queues')) {
            $this->line('  Restart queues: Yes');
        }
        $this->newLine();

        if (! $this->confirmOperation('Do you want to proceed with updating the environment file?')) {
            $this->info('Operation cancelled.');

            return self::SUCCESS;
        }

        $this->logOperation('Update environment', [
            'organization' => $organization,
            'server_id' => $serverId,
            'site_id' => $siteId,
        ], 'warning');

        try {
            $body = [
                'environment' => $environment,
            ];

            if ($this->option('cache')) {
                $body['cache'] = true;
            }

            if ($this->option('queues')) {
                $body['queues'] = true;
            }

            if ($this->option('encryption-key')) {
                $body['encryption_key'] = $this->option('encryption-key');
            }

            $response = $forge->sites()->organizationsServersSitesEnvironmentUpdate(
                organization: $organization,
                server: $serverId,
                site: $siteId,
                body: $body
            );

            if (! $response->successful()) {
                $this->logError('Update environment', $response->body(), [
                    'status' => $response->status(),
                    'organization' => $organization,
                    'server_id' => $serverId,
                    'site_id' => $siteId,
                ]);
                $this->error("Failed to update environment: {$response->body()}");

                return self::FAILURE;
            }

            $executionTime = round((microtime(true) - $startTime) * 1000, 2);

            $this->logSuccess('Update environment', [
                'organization' => $organization,
                'server_id' => $serverId,
                'site_id' => $siteId,
                'execution_time_ms' => $executionTime,
            ]);

            $this->info("Environment file updated successfully in {$executionTime}ms");

            return self::SUCCESS;
        } catch (Exception $e) {
            $executionTime = round((microtime(true) - $startTime) * 1000, 2);

            $this->logError('Update environment', $e->getMessage(), [
                'organization' => $organization,
                'server_id' => $serverId,
                'site_id' => $siteId,
                'execution_time_ms' => $executionTime,
            ]);

            $this->error("Error: {$e->getMessage()}");

            return self::FAILURE;
        }
    }
}
