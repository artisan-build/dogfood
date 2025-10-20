<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeClient;

use ArtisanBuild\ForgeClient\Exceptions\ApiException;
use ArtisanBuild\ForgeClient\Exceptions\AuthenticationException;
use ArtisanBuild\ForgeClient\Exceptions\RateLimitException;
use ArtisanBuild\ForgeClient\Resource\BackgroundProcesses;
use ArtisanBuild\ForgeClient\Resource\Commands;
use ArtisanBuild\ForgeClient\Resource\Databases;
use ArtisanBuild\ForgeClient\Resource\Deployments;
use ArtisanBuild\ForgeClient\Resource\FirewallRules;
use ArtisanBuild\ForgeClient\Resource\Integrations;
use ArtisanBuild\ForgeClient\Resource\Logs;
use ArtisanBuild\ForgeClient\Resource\Monitors;
use ArtisanBuild\ForgeClient\Resource\Nginx;
use ArtisanBuild\ForgeClient\Resource\Organizations;
use ArtisanBuild\ForgeClient\Resource\Providers;
use ArtisanBuild\ForgeClient\Resource\Recipes;
use ArtisanBuild\ForgeClient\Resource\RedirectRules;
use ArtisanBuild\ForgeClient\Resource\Roles;
use ArtisanBuild\ForgeClient\Resource\ScheduledJobs;
use ArtisanBuild\ForgeClient\Resource\SecurityRules;
use ArtisanBuild\ForgeClient\Resource\ServerCredentials;
use ArtisanBuild\ForgeClient\Resource\Servers;
use ArtisanBuild\ForgeClient\Resource\Sites;
use ArtisanBuild\ForgeClient\Resource\SshKeys;
use ArtisanBuild\ForgeClient\Resource\Teams;
use ArtisanBuild\ForgeClient\Resource\User;
use Saloon\Http\Connector;
use Saloon\Http\Response;

/**
 * Forge
 *
 * Laravel Forge - API Documentation
 */
class ForgeClient extends Connector
{
    public function resolveBaseUrl(): string
    {
        return 'https://forge.laravel.com/api';
    }

    public function backgroundProcesses(): BackgroundProcesses
    {
        return new BackgroundProcesses($this);
    }

    public function commands(): Commands
    {
        return new Commands($this);
    }

    public function databases(): Databases
    {
        return new Databases($this);
    }

    public function deployments(): Deployments
    {
        return new Deployments($this);
    }

    public function firewallRules(): FirewallRules
    {
        return new FirewallRules($this);
    }

    public function integrations(): Integrations
    {
        return new Integrations($this);
    }

    public function logs(): Logs
    {
        return new Logs($this);
    }

    public function monitors(): Monitors
    {
        return new Monitors($this);
    }

    public function nginx(): Nginx
    {
        return new Nginx($this);
    }

    public function organizations(): Organizations
    {
        return new Organizations($this);
    }

    public function providers(): Providers
    {
        return new Providers($this);
    }

    public function recipes(): Recipes
    {
        return new Recipes($this);
    }

    public function redirectRules(): RedirectRules
    {
        return new RedirectRules($this);
    }

    public function roles(): Roles
    {
        return new Roles($this);
    }

    public function scheduledJobs(): ScheduledJobs
    {
        return new ScheduledJobs($this);
    }

    public function securityRules(): SecurityRules
    {
        return new SecurityRules($this);
    }

    public function serverCredentials(): ServerCredentials
    {
        return new ServerCredentials($this);
    }

    public function servers(): Servers
    {
        return new Servers($this);
    }

    public function sites(): Sites
    {
        return new Sites($this);
    }

    public function sshKeys(): SshKeys
    {
        return new SshKeys($this);
    }

    public function teams(): Teams
    {
        return new Teams($this);
    }

    public function user(): User
    {
        return new User($this);
    }

    /**
     * Handle HTTP responses and throw appropriate exceptions for error statuses.
     */
    public function getResponse(Response $response): Response
    {
        $status = $response->status();

        // Success responses (2xx) - return as-is
        if ($status >= 200 && $status < 300) {
            return $response;
        }

        // Get response data for error context
        $responseData = $response->json() ?? [];
        $endpoint = $response->getPendingRequest()->getUrl();
        $method = $response->getPendingRequest()->getMethod()->value;

        // Handle specific error statuses
        match ($status) {
            401 => throw AuthenticationException::unauthenticated($endpoint, $method),
            403 => throw AuthenticationException::forbidden($endpoint, $method, $responseData['message'] ?? null),
            429 => throw RateLimitException::fromRetryAfter(
                retryAfterSeconds: $this->getRetryAfterSeconds($response),
                endpoint: $endpoint,
                method: $method
            ),
            default => throw ApiException::fromResponse($status, $responseData, $endpoint, $method),
        };
    }

    /**
     * Extract retry-after seconds from response headers.
     */
    protected function getRetryAfterSeconds(Response $response): ?int
    {
        $retryAfter = $response->header('Retry-After');

        if ($retryAfter === null) {
            return null;
        }

        // Retry-After can be either seconds (integer) or HTTP date
        if (is_numeric($retryAfter)) {
            return (int) $retryAfter;
        }

        // If it's a date, calculate seconds from now
        $retryDate = strtotime($retryAfter);
        if ($retryDate !== false) {
            return max(0, $retryDate - time());
        }

        return null;
    }
}
