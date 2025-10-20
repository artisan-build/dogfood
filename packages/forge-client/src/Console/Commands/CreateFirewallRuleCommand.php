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

class CreateFirewallRuleCommand extends Command
{
    use HandlesDefaultArguments;
    use LogsForgeOperations;
    use PerformsDestructiveForgeOperations;
    use ResolvesResourceIdentifiers;

    protected $signature = 'forge:create-firewall-rule
                            {server? : The server name or ID}
                            {organization? : The organization slug or ID}
                            {--dangerously-skip-confirmation : Skip confirmation prompt}';

    protected $description = 'Create a new firewall rule on a server';

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

        $this->warn('You are about to CREATE a new firewall rule on the server.');
        $this->line("  Organization: {$organization}");
        $this->line("  Server ID: {$serverId}");
        $this->newLine();

        if (! $this->confirmOperation('Do you want to proceed with creating this firewall rule?')) {
            $this->info('Operation cancelled.');

            return self::SUCCESS;
        }

        $this->logOperation('Create firewall rule', [
            'organization' => $organization,
            'server_id' => $serverId,
        ], 'warning');

        try {
            $response = $forge->firewallRules()->organizationsServersFirewallRulesStore(
                organization: $organization,
                server: $serverId
            );

            if (! $response->successful()) {
                $this->logError('Create firewall rule', $response->body(), [
                    'status' => $response->status(),
                    'organization' => $organization,
                    'server_id' => $serverId,
                ]);
                $this->error("Failed to create firewall rule: {$response->body()}");

                return self::FAILURE;
            }

            $data = $response->json();
            $rule = $data['data'] ?? $data;

            $executionTime = round((microtime(true) - $startTime) * 1000, 2);

            $this->logSuccess('Create firewall rule', [
                'organization' => $organization,
                'server_id' => $serverId,
                'rule_id' => $rule['id'] ?? 'N/A',
                'execution_time_ms' => $executionTime,
            ]);

            $this->info("Firewall rule created successfully in {$executionTime}ms");
            $this->table(
                ['Property', 'Value'],
                [
                    ['ID', $rule['id'] ?? 'N/A'],
                    ['Name', $rule['name'] ?? 'N/A'],
                    ['Type', $rule['type'] ?? 'N/A'],
                    ['IP Address', $rule['ip_address'] ?? 'N/A'],
                    ['Port', $rule['port'] ?? 'N/A'],
                    ['Status', $rule['status'] ?? 'N/A'],
                ]
            );

            return self::SUCCESS;
        } catch (Exception $e) {
            $executionTime = round((microtime(true) - $startTime) * 1000, 2);

            $this->logError('Create firewall rule', $e->getMessage(), [
                'organization' => $organization,
                'server_id' => $serverId,
                'execution_time_ms' => $executionTime,
            ]);

            $this->error("Error: {$e->getMessage()}");

            return self::FAILURE;
        }
    }
}
