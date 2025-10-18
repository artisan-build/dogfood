# Technical Specification

This is the technical specification for the spec detailed in @.agent-os/specs/2025-10-18-forge-sdk/spec.md

> Created: 2025-10-18
> Version: 1.0.0

## Technical Requirements

### Saloon SDK Architecture

- **Connector Class**: `ForgeConnector` extending `Saloon\Http\Connector` with base URL, authentication, and default headers
- **Resource Classes**: Organize requests into logical groups (Servers, Sites, Databases, SSL, Daemons, Workers, ScheduledJobs, Deployments, Recipes, Credentials, Circles, Webhooks, Backups)
- **Request Classes**: Individual request class for each API endpoint extending `Saloon\Http\Request`
- **Response DTOs**: Data transfer objects for complex responses (optional but recommended for better type safety)
- **Enum Classes**: PHP 8.1+ string-backed enums for all enumerated API values

### API Coverage

Based on the new Laravel Forge API, the SDK must cover:

1. **Authentication** - API token authentication via Bearer token
2. **Servers** - Create, list, update, delete, reboot servers
3. **Sites** - Manage sites on servers
4. **SSL Certificates** - Install, activate, manage SSL certificates
5. **Databases** - Create, manage databases and database users
6. **Background Processes (Daemons)** - Create and manage background processes
7. **Commands** - Run commands on servers
8. **Deployments** - Trigger and manage deployments
9. **Firewall Rules** - Manage server firewall rules
10. **Integrations** - Manage source control provider integrations
11. **Logs** - Access server and application logs
12. **Monitors** - Manage server monitors
13. **Nginx** - Manage nginx configuration
14. **Organizations** - Manage Forge organizations
15. **Providers** - Manage cloud providers
16. **Recipes** - Manage server recipes
17. **Redirect Rules** - Manage site redirect rules
18. **Roles** - Manage organization roles
19. **SSH Keys** - Manage SSH keys
20. **Security Rules** - Manage security rules
21. **Sites** - Comprehensive site management
22. **Webhooks** - Manage deployment webhooks (part of deployments)

### Validation Requirements

- **Pre-request Validation**: Validate all parameters before sending requests to avoid API abuse
- **Enum Validation**: All enumerated values must use PHP enums with `tryFrom()` or explicit validation
- **Type Hints**: Strict typing on all method parameters and return types
- **Exception Handling**: Throw descriptive exceptions for validation failures before API calls

### Laravel Integration

- **Service Provider**: `ForgeServiceProvider` for package registration
- **Configuration File**: `config/forge.php` with API token and default settings
- **Facade**: `Forge` facade for convenient access (optional)
- **Artisan Commands**: Command for each major resource group to test endpoints manually
- **Minimal Dependencies**: Keep Laravel-specific dependencies minimal for Laravel Zero compatibility

### Testing Strategy

- **HTTP Mocking**: Use Saloon's MockClient for all test requests
- **Fixture Responses**: Create JSON fixtures for all API responses based on Forge API documentation
- **Unit Tests**: Test each request class validates parameters correctly
- **Integration Tests**: Test resource classes compose requests properly
- **Manual Testing Commands**: Artisan commands for real API testing during development

### Documentation Requirements

- **README Structure**:
  - Table of Contents (auto-linked)
  - Installation instructions
  - Configuration guide
  - Quick Start example
  - Usage examples for every resource
  - Command reference for all artisan commands
  - Error handling guide
  - Troubleshooting section
  - Contributing guide
  - Changelog link
- **Inline Documentation**: PHPDoc blocks on all public methods with `@param`, `@return`, `@throws` tags
- **Code Examples**: Working code examples in README for common use cases

## Approach Options

### Option A: Framework-Agnostic Core with Laravel Wrapper

Build the SDK as a pure PHP library with Saloon, then create a separate Laravel-specific wrapper package.

**Pros:**
- Reusable in non-Laravel contexts
- Clear separation of concerns
- Could distribute as two packages

**Cons:**
- More complex project structure
- Overkill given unlikely non-Laravel usage
- More maintenance burden

### Option B: Laravel Package with Minimal Dependencies (Selected)

Build as a Laravel package from the start, but keep Laravel-specific features minimal and optional.

**Pros:**
- Simpler architecture and maintenance
- Works perfectly with Laravel Zero (minimal Laravel)
- Matches actual use case (Forge is Laravel-specific)
- Can still be used in any Composer project with minimal Laravel features

**Cons:**
- Requires Laravel framework (even if minimal)
- Not truly framework-agnostic

**Rationale:** Given that Forge is a Laravel product and the primary use case is Laravel/Laravel Zero applications, building a Laravel package makes the most sense. We can keep dependencies minimal to maintain Laravel Zero compatibility.

### Option C: Pure Saloon SDK (No Laravel Integration)

Build only with Saloon, no Laravel-specific features at all.

**Pros:**
- Maximum portability
- Fewest dependencies
- Truly framework-agnostic

**Cons:**
- Users lose Laravel conveniences (config, service provider, commands)
- Doesn't match requirement for Laravel package
- Would need separate package for Laravel integration anyway

## External Dependencies

### Required Dependencies

- **saloonphp/saloon** (^3.0) - Core HTTP client and SDK builder
  - **Justification**: Purpose-built for creating SDKs with excellent architecture patterns, testing support, and developer experience

- **illuminate/support** (^11.0) - Laravel support package
  - **Justification**: Required for service provider, configuration, and collection helpers. Minimal footprint and compatible with Laravel Zero.

- **illuminate/console** (^11.0) - Laravel console package
  - **Justification**: Required for artisan commands. Compatible with Laravel Zero.

### Development Dependencies

- **pestphp/pest** (^3.0) - Testing framework
  - **Justification**: Already used in monorepo, provides excellent testing DX

- **orchestra/testbench** (^9.0) - Laravel package testing
  - **Justification**: Required for testing Laravel-specific features (service provider, commands, config)

- **saloonphp/saloon-test-support** (^3.0) - Saloon testing utilities
  - **Justification**: Provides MockClient and fixture support for testing HTTP requests

## Package Structure

```
packages/forge-sdk/
├── src/
│   ├── ForgeConnector.php
│   ├── Forge.php (SDK facade class)
│   ├── ForgeServiceProvider.php
│   ├── Resources/
│   │   ├── BackgroundProcessResource.php
│   │   ├── CommandResource.php
│   │   ├── DatabaseResource.php
│   │   ├── DeploymentResource.php
│   │   ├── FirewallRuleResource.php
│   │   ├── IntegrationResource.php
│   │   ├── LogResource.php
│   │   ├── MonitorResource.php
│   │   ├── NginxResource.php
│   │   ├── OrganizationResource.php
│   │   ├── ProviderResource.php
│   │   ├── RecipeResource.php
│   │   ├── RedirectRuleResource.php
│   │   ├── RoleResource.php
│   │   ├── SshKeyResource.php
│   │   ├── ScheduledJobResource.php
│   │   ├── SecurityRuleResource.php
│   │   ├── ServerCredentialResource.php
│   │   ├── ServerResource.php
│   │   ├── SiteResource.php
│   │   ├── TeamResource.php
│   │   ├── UserResource.php
│   │   └── SslCertificateResource.php (sub-resource of Site)
│   ├── Requests/
│   │   ├── BackgroundProcesses/
│   │   ├── Commands/
│   │   ├── Databases/
│   │   ├── Deployments/
│   │   ├── FirewallRules/
│   │   ├── Integrations/
│   │   ├── Logs/
│   │   ├── Monitors/
│   │   ├── Nginx/
│   │   ├── Organizations/
│   │   ├── Providers/
│   │   ├── Recipes/
│   │   ├── RedirectRules/
│   │   ├── Roles/
│   │   ├── SshKeys/
│   │   ├── ScheduledJobs/
│   │   ├── SecurityRules/
│   │   ├── ServerCredentials/
│   │   ├── Servers/
│   │   │   ├── ListServersRequest.php
│   │   │   ├── GetServerRequest.php
│   │   │   ├── CreateServerRequest.php
│   │   │   ├── UpdateServerRequest.php
│   │   │   ├── DeleteServerRequest.php
│   │   │   └── ...
│   │   ├── Sites/
│   │   ├── Teams/
│   │   ├── User/
│   │   └── SslCertificates/
│   ├── Enums/
│   │   ├── ServerSize.php
│   │   ├── ServerType.php
│   │   ├── Region.php
│   │   ├── DatabaseType.php
│   │   ├── PhpVersion.php
│   │   └── ...
│   ├── DataTransferObjects/ (optional)
│   │   ├── Server.php
│   │   ├── Site.php
│   │   └── ...
│   ├── Exceptions/
│   │   ├── ForgeException.php
│   │   ├── ValidationException.php
│   │   └── ...
│   └── Console/
│       ├── Commands/
│       │   ├── TestBackgroundProcessesCommand.php
│       │   ├── TestCommandsCommand.php
│       │   ├── TestDatabasesCommand.php
│       │   ├── TestDeploymentsCommand.php
│       │   ├── TestFirewallRulesCommand.php
│       │   ├── TestIntegrationsCommand.php
│       │   ├── TestLogsCommand.php
│       │   ├── TestMonitorsCommand.php
│       │   ├── TestNginxCommand.php
│       │   ├── TestOrganizationsCommand.php
│       │   ├── TestProvidersCommand.php
│       │   ├── TestRecipesCommand.php
│       │   ├── TestRedirectRulesCommand.php
│       │   ├── TestRolesCommand.php
│       │   ├── TestSshKeysCommand.php
│       │   ├── TestScheduledJobsCommand.php
│       │   ├── TestSecurityRulesCommand.php
│       │   ├── TestServerCredentialsCommand.php
│       │   ├── TestServersCommand.php
│       │   ├── TestSitesCommand.php
│       │   ├── TestSslCertificatesCommand.php
│       │   ├── TestTeamsCommand.php
│       │   └── TestUserCommand.php
│       └── ...
├── tests/
│   ├── Unit/
│   ├── Feature/
│   └── Fixtures/
│       └── responses/
├── config/
│   └── forge.php
├── README.md
├── CHANGELOG.md
├── composer.json
└── phpunit.xml.dist
```

## Configuration Structure

```php
// config/forge.php
return [
    'api_token' => env('FORGE_API_TOKEN'),
    'base_url' => env('FORGE_API_URL', 'https://forge.laravel.com/api'),
    'timeout' => env('FORGE_TIMEOUT', 30),
    'retry' => [
        'times' => env('FORGE_RETRY_TIMES', 3),
        'sleep' => env('FORGE_RETRY_SLEEP', 1000),
    ],
];
```

## Rate Limiting Considerations

The Forge API has a rate limit of 60 requests per minute. While Saloon may provide rate limiting features, we should:
- Document the rate limit clearly in README
- Consider whether to track rate limits in SDK (optional, out of initial scope)
- Let developers handle rate limiting in their applications

## Enum Design Pattern

All enums should follow this pattern:

```php
namespace ArtisanBuild\ForgeSdk\Enums;

enum ServerType: string
{
    case APP = 'app';
    case WEB = 'web';
    case DATABASE = 'database';
    case CACHE = 'cache';
    case WORKER = 'worker';
    case LOAD_BALANCER = 'load-balancer';

    public static function validate(string $value): self
    {
        return self::tryFrom($value)
            ?? throw new ValidationException("Invalid server type: {$value}");
    }
}
```

## Command Design Pattern

Each test command should:
- Accept all required parameters as arguments
- Accept optional parameters as options
- Display formatted output with response data
- Handle errors gracefully with helpful messages
- Include examples in help text

Example:
```bash
php artisan forge:test-server create \
  --name="my-server" \
  --provider="digitalocean" \
  --size="1gb" \
  --region="nyc3"
```
