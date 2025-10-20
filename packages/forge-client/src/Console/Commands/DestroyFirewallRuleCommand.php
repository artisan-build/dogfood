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

class DestroyFirewallRuleCommand extends Command
{
    use HandlesDefaultArguments;
    use LogsForgeOperations;
    use PerformsDestructiveForgeOperations;
    use ResolvesResourceIdentifiers;

    protected $signature = 'forge:destroy-firewall-rule
                            {organization? : The organization slug or ID}
                            {server? : The server name or ID}
                            {rule? : The firewall rule ID}
                            {--dangerously-skip-confirmation : Skip confirmation prompt}';

    protected $description = 'Destroy (delete) a firewall rule';

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

        // Get firewall rule details first for confirmation message
        try {
            $ruleResponse = $forge->firewallRules()->organizationsServersFirewallRulesShow(
                organization: $organization,
                server: $serverId,
                rule: $ruleId
            );

            if (! $ruleResponse->successful()) {
                $this->error("Failed to get firewall rule details: {$ruleResponse->body()}");

                return self::FAILURE;
            }

            $ruleData = $ruleResponse->json();
            $rule = $ruleData['data'] ?? $ruleData;
            $ruleName = $rule['name'] ?? "Firewall Rule {$ruleId}";

            $this->warn('You are about to DESTROY the following firewall rule:');
            $this->line("  ID: {$ruleId}");
            $this->line("  Name: {$ruleName}");
            $this->line("  Organization: {$organization}");
            $this->line("  Server ID: {$serverId}");
            $this->newLine();
            $this->error('This action is IRREVERSIBLE and will permanently delete the firewall rule.');
            $this->newLine();

            if (! $this->confirmOperation("Type 'yes' to confirm you want to destroy firewall rule '{$ruleName}'")) {
                $this->info('Operation cancelled.');

                return self::SUCCESS;
            }
        } catch (Exception $e) {
            $executionTime = round((microtime(true) - $startTime) * 1000, 2);

            $this->logError('Get firewall rule details before destroy', $e->getMessage(), [
                'organization' => $organization,
                'server_id' => $serverId,
                'rule_id' => $ruleId,
                'execution_time_ms' => $executionTime,
            ]);

            $this->error("Unable to retrieve firewall rule details: {$e->getMessage()}");
            $this->error('Cannot safely destroy firewall rule without confirming details.');

            return self::FAILURE;
        }

        $this->logOperation('Destroy firewall rule', [
            'organization' => $organization,
            'server_id' => $serverId,
            'rule_id' => $ruleId,
        ], 'error'); // Error level for audit trail

        try {
            $response = $forge->firewallRules()->organizationsServersFirewallRulesDestroy(
                organization: $organization,
                server: $serverId,
                rule: $ruleId
            );

            if (! $response->successful()) {
                $this->logError('Destroy firewall rule', $response->body(), [
                    'status' => $response->status(),
                    'organization' => $organization,
                    'server_id' => $serverId,
                    'rule_id' => $ruleId,
                ]);
                $this->error("Failed to destroy firewall rule: {$response->body()}");

                return self::FAILURE;
            }

            $executionTime = round((microtime(true) - $startTime) * 1000, 2);

            $this->logSuccess('Destroy firewall rule', [
                'organization' => $organization,
                'server_id' => $serverId,
                'rule_id' => $ruleId,
                'execution_time_ms' => $executionTime,
            ]);

            $this->info("Firewall rule destroyed successfully in {$executionTime}ms");

            return self::SUCCESS;
        } catch (Exception $e) {
            $executionTime = round((microtime(true) - $startTime) * 1000, 2);

            $this->logError('Destroy firewall rule', $e->getMessage(), [
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
