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
use InvalidArgumentException;

class DestroySslCertificateCommand extends Command
{
    use HandlesDefaultArguments;
    use LogsForgeOperations;
    use PerformsDestructiveForgeOperations;
    use ResolvesResourceIdentifiers;

    protected $signature = 'forge:destroy-ssl-certificate
                            {organization? : The organization slug or ID}
                            {server? : The server name or ID}
                            {site? : The site ID}
                            {domain-record? : The domain record ID}
                            {--dangerously-skip-confirmation : Skip confirmation prompt}';

    protected $description = 'Destroy (delete) an SSL certificate for a site domain';

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
        } catch (InvalidArgumentException $e) {
            $this->error($e->getMessage());

            return self::FAILURE;
        }

        // Get SSL certificate details first for confirmation message
        try {
            $certificateResponse = $forge->sites()->organizationsServersSitesDomainsCertificateShow(
                organization: $organization,
                server: $serverId,
                site: $siteId,
                domainRecord: $domainRecordId
            );

            if (! $certificateResponse->successful()) {
                $this->error("Failed to get SSL certificate details: {$certificateResponse->body()}");

                return self::FAILURE;
            }

            $certificateData = $certificateResponse->json();
            $certificate = $certificateData['data'] ?? $certificateData;
            $certificateDomain = $certificate['domain'] ?? "Domain Record {$domainRecordId}";

            $this->warn('You are about to DESTROY the SSL certificate for:');
            $this->line("  Domain: {$certificateDomain}");
            $this->line("  Organization: {$organization}");
            $this->line("  Server ID: {$serverId}");
            $this->line("  Site ID: {$siteId}");
            $this->line("  Domain Record ID: {$domainRecordId}");
            $this->newLine();
            $this->error('This action will remove SSL encryption from the domain.');
            $this->error('The site will no longer be accessible via HTTPS until a new certificate is installed.');
            $this->newLine();

            if (! $this->confirmOperation("Type 'yes' to confirm you want to destroy the SSL certificate for '{$certificateDomain}'")) {
                $this->info('Operation cancelled.');

                return self::SUCCESS;
            }
        } catch (Exception $e) {
            $executionTime = round((microtime(true) - $startTime) * 1000, 2);

            $this->logError('Get SSL certificate details before destroy', $e->getMessage(), [
                'organization' => $organization,
                'server_id' => $serverId,
                'site_id' => $siteId,
                'domain_record_id' => $domainRecordId,
                'execution_time_ms' => $executionTime,
            ]);

            $this->error("Unable to retrieve SSL certificate details: {$e->getMessage()}");
            $this->error('Cannot safely destroy SSL certificate without confirming details.');

            return self::FAILURE;
        }

        $this->logOperation('Destroy SSL certificate', [
            'organization' => $organization,
            'server_id' => $serverId,
            'site_id' => $siteId,
            'domain_record_id' => $domainRecordId,
        ], 'error'); // Error level for audit trail

        try {
            $response = $forge->sites()->organizationsServersSitesDomainsCertificateDestroy(
                organization: $organization,
                server: $serverId,
                site: $siteId,
                domainRecord: $domainRecordId
            );

            if (! $response->successful()) {
                $this->logError('Destroy SSL certificate', $response->body(), [
                    'status' => $response->status(),
                    'organization' => $organization,
                    'server_id' => $serverId,
                    'site_id' => $siteId,
                    'domain_record_id' => $domainRecordId,
                ]);
                $this->error("Failed to destroy SSL certificate: {$response->body()}");

                return self::FAILURE;
            }

            $executionTime = round((microtime(true) - $startTime) * 1000, 2);

            $this->logSuccess('Destroy SSL certificate', [
                'organization' => $organization,
                'server_id' => $serverId,
                'site_id' => $siteId,
                'domain_record_id' => $domainRecordId,
                'execution_time_ms' => $executionTime,
            ]);

            $this->info("SSL certificate destroyed successfully in {$executionTime}ms");

            return self::SUCCESS;
        } catch (Exception $e) {
            $executionTime = round((microtime(true) - $startTime) * 1000, 2);

            $this->logError('Destroy SSL certificate', $e->getMessage(), [
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
