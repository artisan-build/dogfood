<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeSdk\Console\Commands;

use ArtisanBuild\ForgeSdk\Console\Concerns\HandlesDefaultArguments;
use ArtisanBuild\ForgeSdk\Console\Concerns\LogsForgeOperations;
use ArtisanBuild\ForgeSdk\Console\Concerns\ResolvesResourceIdentifiers;
use ArtisanBuild\ForgeSdk\ForgeSdk;
use Exception;
use Illuminate\Console\Command;

class ListFirewallRulesCommand extends Command
{
    use HandlesDefaultArguments;
    use LogsForgeOperations;
    use ResolvesResourceIdentifiers;

    protected $signature = 'forge:list-firewall-rules
                            {organization? : The organization slug or ID}
                            {server? : The server name or ID}
                            {--sort= : Sort by (created_at, updated_at)}
                            {--pagesize= : Number of results per page}
                            {--pagecursor= : Cursor for pagination}
                            {--filter-name= : Filter by firewall rule name}
                            {--filter-status= : Filter by status}
                            {--filter-ip-address= : Filter by IP address or subnet}
                            {--filter-type= : Filter by firewall rule type}
                            {--filter-port= : Filter by port or port range}';

    protected $description = 'List all firewall rules for a server';

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
        $filterName = $this->option('filter-name');
        $filterStatus = $this->option('filter-status');
        $filterIpAddress = $this->option('filter-ip-address');
        $filterType = $this->option('filter-type');
        $filterPort = $this->option('filter-port');

        $this->logOperation('List firewall rules', [
            'organization' => $organization,
            'server_id' => $serverId,
            'sort' => $sort,
            'pagesize' => $pagesize,
        ]);

        try {
            $response = $forge->firewallRules()->organizationsServersFirewallRulesIndex(
                organization: $organization,
                server: $serverId,
                sort: $sort,
                pagesize: $pagesize,
                pagecursor: $pagecursor,
                filtername: $filterName,
                filterstatus: $filterStatus,
                filteripAddress: $filterIpAddress,
                filtertype: $filterType,
                filterport: $filterPort,
            );

            if (! $response->successful()) {
                $this->logError('List firewall rules', $response->body(), [
                    'status' => $response->status(),
                    'organization' => $organization,
                    'server_id' => $serverId,
                ]);
                $this->error("Failed to list firewall rules: {$response->body()}");

                return self::FAILURE;
            }

            $data = $response->json();
            $rules = $data['data'] ?? [];

            $this->table(
                ['ID', 'Name', 'Type', 'IP Address', 'Port', 'Status', 'Created At'],
                collect($rules)->map(fn ($rule) => [
                    $rule['id'] ?? 'N/A',
                    $rule['name'] ?? 'N/A',
                    $rule['type'] ?? 'N/A',
                    $rule['ip_address'] ?? 'N/A',
                    $rule['port'] ?? 'N/A',
                    $rule['status'] ?? 'N/A',
                    $rule['created_at'] ?? 'N/A',
                ])->all()
            );

            $executionTime = round((microtime(true) - $startTime) * 1000, 2);

            $this->logSuccess('List firewall rules', [
                'organization' => $organization,
                'server_id' => $serverId,
                'count' => count($rules),
                'execution_time_ms' => $executionTime,
            ]);

            $this->info('Listed '.count($rules)." firewall rule(s) in {$executionTime}ms");

            return self::SUCCESS;
        } catch (Exception $e) {
            $executionTime = round((microtime(true) - $startTime) * 1000, 2);

            $this->logError('List firewall rules', $e->getMessage(), [
                'organization' => $organization,
                'server_id' => $serverId,
                'execution_time_ms' => $executionTime,
            ]);

            $this->error("Error: {$e->getMessage()}");

            return self::FAILURE;
        }
    }
}
