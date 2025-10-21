<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeClient\Console\Commands;

use ArtisanBuild\ForgeClient\Console\Concerns\HandlesDefaultArguments;
use ArtisanBuild\ForgeClient\Console\Concerns\LogsForgeOperations;
use ArtisanBuild\ForgeClient\Console\Concerns\PerformsDestructiveForgeOperations;
use ArtisanBuild\ForgeClient\Console\Concerns\ResolvesResourceIdentifiers;
use ArtisanBuild\ForgeClient\Exceptions\ProtectedResourceException;
use ArtisanBuild\ForgeClient\ForgeClient;
use Exception;
use Illuminate\Console\Command;
use InvalidArgumentException;

class DestroySiteCommand extends Command
{
    use HandlesDefaultArguments;
    use LogsForgeOperations;
    use PerformsDestructiveForgeOperations;
    use ResolvesResourceIdentifiers;

    protected $signature = 'forge:destroy-site
                            {site? : The site name or ID}
                            {server? : The server name or ID}
                            {organization? : The organization slug or ID}
                            {--dangerously-skip-confirmation : Skip confirmation prompt}';

    protected $description = 'Destroy a site';

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

        try {
            $organization = $this->resolveOrganizationSlug($organizationInput, $forge);
            $server = $this->resolveServerIdentifier($serverInput, $organization, $forge);
            $site = $this->resolveSiteIdentifier($siteInput, $organization, $server, $forge);
        } catch (InvalidArgumentException $e) {
            $this->error($e->getMessage());

            return self::FAILURE;
        }

        $this->warn('You are about to destroy the following site:');
        $this->line("  Organization: {$organization}");
        $this->line("  Server: {$server}");
        $this->line("  Site: {$site}");
        $this->newLine();
        $this->warn('This action cannot be undone!');
        $this->newLine();

        if (! $this->confirmOperation('Are you sure you want to destroy this site?')) {
            $this->info('Operation cancelled.');

            return self::SUCCESS;
        }

        $this->logOperation('Destroy site', [
            'organization' => $organization,
            'server' => $server,
            'site' => $site,
        ], 'error');

        try {
            $response = $forge->sites()->organizationsServersSitesDestroy($organization, $server, $site);

            if (! $response->successful()) {
                $this->logError('Destroy site', $response->body(), [
                    'status' => $response->status(),
                    'organization' => $organization,
                    'server' => $server,
                    'site' => $site,
                ]);
                $this->error("Failed to destroy site: {$response->body()}");

                return self::FAILURE;
            }

            $executionTime = round((microtime(true) - $startTime) * 1000, 2);

            $this->logSuccess('Destroy site', [
                'organization' => $organization,
                'server' => $server,
                'site' => $site,
                'execution_time_ms' => $executionTime,
            ]);

            $this->info('Site destroyed successfully!');
            $this->comment("Site destroyed in {$executionTime}ms");

            return self::SUCCESS;
        } catch (ProtectedResourceException $e) {
            $this->newLine();
            $this->error('PROTECTED RESOURCE');
            $this->newLine();
            $this->line("Site {$site} is marked as protected because it is critical to your business operations.");
            $this->line('This site cannot be deleted via the SDK to prevent accidental data loss.');
            $this->newLine();
            $this->comment('What to do:');
            $this->line('  • If this site has been replaced, update config/forge-client.php');
            $this->line("  • Remove site ID {$site} from the 'protected_sites' array");
            $this->line("  • Add the new site's ID to 'protected_sites' if needed");
            $this->line('  • If you still need to delete this site, do so through the Forge UI');
            $this->newLine();

            $this->logError('Protected site deletion attempt', $e->getMessage(), [
                'organization' => $organization,
                'server' => $server,
                'site' => $site,
            ]);

            return self::FAILURE;
        } catch (Exception $e) {
            $executionTime = round((microtime(true) - $startTime) * 1000, 2);

            $this->logError('Destroy site', $e->getMessage(), [
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
