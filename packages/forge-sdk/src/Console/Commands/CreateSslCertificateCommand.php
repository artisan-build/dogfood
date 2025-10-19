<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeSdk\Console\Commands;

use ArtisanBuild\ForgeSdk\Console\Concerns\HandlesDefaultArguments;
use ArtisanBuild\ForgeSdk\Console\Concerns\LogsForgeOperations;
use ArtisanBuild\ForgeSdk\Console\Concerns\PerformsDestructiveForgeOperations;
use ArtisanBuild\ForgeSdk\Console\Concerns\ResolvesResourceIdentifiers;
use ArtisanBuild\ForgeSdk\ForgeSdk;
use Exception;
use Illuminate\Console\Command;

class CreateSslCertificateCommand extends Command
{
    use HandlesDefaultArguments;
    use LogsForgeOperations;
    use PerformsDestructiveForgeOperations;
    use ResolvesResourceIdentifiers;

    protected $signature = 'forge:create-ssl-certificate
                            {organization? : The organization slug or ID}
                            {server? : The server name or ID}
                            {site? : The site ID}
                            {domain-record? : The domain record ID}
                            {--dangerously-skip-confirmation : Skip confirmation prompt}';

    protected $description = 'Create and install an SSL certificate for a site domain';

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
        $siteId = (int) $this->argument('site');
        $domainRecordId = (int) $this->argument('domain-record');

        try {
            $organization = $this->resolveOrganizationSlug($organizationInput, $forge);
            $serverId = $this->resolveServerId($serverInput, $organization, $forge);
        } catch (\InvalidArgumentException $e) {
            $this->error($e->getMessage());

            return self::FAILURE;
        }

        $this->warn('You are about to CREATE and INSTALL an SSL certificate for a site domain.');
        $this->line("  Organization: {$organization}");
        $this->line("  Server ID: {$serverId}");
        $this->line("  Site ID: {$siteId}");
        $this->line("  Domain Record ID: {$domainRecordId}");
        $this->newLine();

        if (! $this->confirmOperation('Do you want to proceed with creating this SSL certificate?')) {
            $this->info('Operation cancelled.');

            return self::SUCCESS;
        }

        $this->logOperation('Create SSL certificate', [
            'organization' => $organization,
            'server_id' => $serverId,
            'site_id' => $siteId,
            'domain_record_id' => $domainRecordId,
        ], 'warning');

        try {
            $response = $forge->sites()->organizationsServersSitesDomainsCertificateStore(
                organization: $organization,
                server: $serverId,
                site: $siteId,
                domainRecord: $domainRecordId
            );

            if (! $response->successful()) {
                $this->logError('Create SSL certificate', $response->body(), [
                    'status' => $response->status(),
                    'organization' => $organization,
                    'server_id' => $serverId,
                    'site_id' => $siteId,
                    'domain_record_id' => $domainRecordId,
                ]);
                $this->error("Failed to create SSL certificate: {$response->body()}");

                return self::FAILURE;
            }

            $data = $response->json();
            $certificate = $data['data'] ?? $data;

            $executionTime = round((microtime(true) - $startTime) * 1000, 2);

            $this->logSuccess('Create SSL certificate', [
                'organization' => $organization,
                'server_id' => $serverId,
                'site_id' => $siteId,
                'domain_record_id' => $domainRecordId,
                'certificate_id' => $certificate['id'] ?? 'N/A',
                'execution_time_ms' => $executionTime,
            ]);

            $this->info("SSL certificate created successfully in {$executionTime}ms");
            $this->table(
                ['Property', 'Value'],
                [
                    ['ID', $certificate['id'] ?? 'N/A'],
                    ['Type', $certificate['type'] ?? 'N/A'],
                    ['Status', $certificate['status'] ?? 'N/A'],
                    ['Domain', $certificate['domain'] ?? 'N/A'],
                    ['Active', ($certificate['active'] ?? false) ? 'Yes' : 'No'],
                ]
            );

            return self::SUCCESS;
        } catch (Exception $e) {
            $executionTime = round((microtime(true) - $startTime) * 1000, 2);

            $this->logError('Create SSL certificate', $e->getMessage(), [
                'organization' => $organization,
                'server_id' => $serverId,
                'site_id' => $siteId,
                'domain_record_id' => $domainRecordId,
                'execution_time_ms' => $executionTime,
            ]);

            $this->error("Error: {$e->getMessage()}");

            return self::FAILURE;
        }
    }
}
