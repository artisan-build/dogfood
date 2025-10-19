<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeSdk\Console\Commands;

use ArtisanBuild\ForgeSdk\Console\Concerns\HandlesDefaultArguments;
use ArtisanBuild\ForgeSdk\Console\Concerns\LogsForgeOperations;
use ArtisanBuild\ForgeSdk\Console\Concerns\ResolvesResourceIdentifiers;
use ArtisanBuild\ForgeSdk\ForgeSdk;
use Exception;
use Illuminate\Console\Command;

class GetSslCertificateCommand extends Command
{
    use HandlesDefaultArguments;
    use LogsForgeOperations;
    use ResolvesResourceIdentifiers;

    protected $signature = 'forge:get-ssl-certificate
                            {organization? : The organization slug or ID}
                            {server? : The server name or ID}
                            {site? : The site ID}
                            {domain-record? : The domain record ID}';

    protected $description = 'Get SSL certificate details for a specific site domain';

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

        $this->logOperation('Get SSL certificate', [
            'organization' => $organization,
            'server_id' => $serverId,
            'site_id' => $siteId,
            'domain_record_id' => $domainRecordId,
        ]);

        try {
            $response = $forge->sites()->organizationsServersSitesDomainsCertificateShow(
                organization: $organization,
                server: $serverId,
                site: $siteId,
                domainRecord: $domainRecordId
            );

            if (! $response->successful()) {
                $this->logError('Get SSL certificate', $response->body(), [
                    'status' => $response->status(),
                    'organization' => $organization,
                    'server_id' => $serverId,
                    'site_id' => $siteId,
                    'domain_record_id' => $domainRecordId,
                ]);
                $this->error("Failed to get SSL certificate: {$response->body()}");

                return self::FAILURE;
            }

            $data = $response->json();
            $certificate = $data['data'] ?? $data;

            $this->table(
                ['Property', 'Value'],
                [
                    ['ID', $certificate['id'] ?? 'N/A'],
                    ['Type', $certificate['type'] ?? 'N/A'],
                    ['Status', $certificate['status'] ?? 'N/A'],
                    ['Domain', $certificate['domain'] ?? 'N/A'],
                    ['Active', ($certificate['active'] ?? false) ? 'Yes' : 'No'],
                    ['Existing', ($certificate['existing'] ?? false) ? 'Yes' : 'No'],
                    ['Created At', $certificate['created_at'] ?? 'N/A'],
                    ['Updated At', $certificate['updated_at'] ?? 'N/A'],
                ]
            );

            $executionTime = round((microtime(true) - $startTime) * 1000, 2);

            $this->logSuccess('Get SSL certificate', [
                'organization' => $organization,
                'server_id' => $serverId,
                'site_id' => $siteId,
                'domain_record_id' => $domainRecordId,
                'execution_time_ms' => $executionTime,
            ]);

            $this->info("Retrieved SSL certificate details in {$executionTime}ms");

            return self::SUCCESS;
        } catch (Exception $e) {
            $executionTime = round((microtime(true) - $startTime) * 1000, 2);

            $this->logError('Get SSL certificate', $e->getMessage(), [
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
