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

class GetFirewallRuleCommand extends Command
{
    use HandlesDefaultArguments;
    use LogsForgeOperations;
    use ResolvesResourceIdentifiers;

    protected $signature = 'forge:get-firewall-rule
                            {organization? : The organization slug or ID}
                            {server? : The server name or ID}
                            {rule? : The firewall rule ID}';

    protected $description = 'Get details for a specific firewall rule';

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
        $ruleId = (int) $this->argument('rule');

        try {
            $organization = $this->resolveOrganizationSlug($organizationInput, $forge);
            $serverId = $this->resolveServerId($serverInput, $organization, $forge);
        } catch (InvalidArgumentException $e) {
            $this->error($e->getMessage());

            return self::FAILURE;
        }

        $this->logOperation('Get firewall rule', [
            'organization' => $organization,
            'server_id' => $serverId,
            'rule_id' => $ruleId,
        ]);

        try {
            $response = $forge->firewallRules()->organizationsServersFirewallRulesShow(
                organization: $organization,
                server: $serverId,
                rule: $ruleId
            );

            if (! $response->successful()) {
                $this->logError('Get firewall rule', $response->body(), [
                    'status' => $response->status(),
                    'organization' => $organization,
                    'server_id' => $serverId,
                    'rule_id' => $ruleId,
                ]);
                $this->error("Failed to get firewall rule: {$response->body()}");

                return self::FAILURE;
            }

            $data = $response->json();
            $rule = $data['data'] ?? $data;

            $this->table(
                ['Property', 'Value'],
                [
                    ['ID', $rule['id'] ?? 'N/A'],
                    ['Name', $rule['name'] ?? 'N/A'],
                    ['Type', $rule['type'] ?? 'N/A'],
                    ['IP Address', $rule['ip_address'] ?? 'N/A'],
                    ['Port', $rule['port'] ?? 'N/A'],
                    ['Status', $rule['status'] ?? 'N/A'],
                    ['Created At', $rule['created_at'] ?? 'N/A'],
                    ['Updated At', $rule['updated_at'] ?? 'N/A'],
                ]
            );

            $executionTime = round((microtime(true) - $startTime) * 1000, 2);

            $this->logSuccess('Get firewall rule', [
                'organization' => $organization,
                'server_id' => $serverId,
                'rule_id' => $ruleId,
                'execution_time_ms' => $executionTime,
            ]);

            $this->info("Retrieved firewall rule details in {$executionTime}ms");

            return self::SUCCESS;
        } catch (Exception $e) {
            $executionTime = round((microtime(true) - $startTime) * 1000, 2);

            $this->logError('Get firewall rule', $e->getMessage(), [
                'organization' => $organization,
                'server_id' => $serverId,
                'rule_id' => $ruleId,
                'execution_time_ms' => $executionTime,
            ]);

            $this->error("Error: {$e->getMessage()}");

            return self::FAILURE;
        }
    }
}
