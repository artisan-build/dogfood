# Technical Specification

This is the technical specification for the spec detailed in @.agent-os/specs/2025-10-18-forge-sdk/spec.md

> Created: 2025-10-18
> Version: 1.0.0

## OpenAPI Specification

**SOURCE OF TRUTH**: The Laravel Forge API provides an official OpenAPI 3.1.0 specification at `https://forge.laravel.com/api/docs.openapi`.

This specification file has been downloaded and saved to `@.agent-os/specs/2025-10-18-forge-sdk/openapi-spec.json` and contains:
- **132 API endpoints** (paths)
- **195 schemas** (data models)
- Complete request/response definitions
- Authentication details
- Validation rules

**This OpenAPI spec is the authoritative source for all API implementation details.**

## SDK Generation Strategy

Instead of manually writing all connectors, requests, and resources, we will use the **Saloon SDK Generator** to automatically generate the bulk of the SDK from the OpenAPI specification.

### Saloon SDK Generator

The `saloonphp/saloon-sdk-generator` package can:
- Generate connector classes from OpenAPI specs
- Generate request classes for each endpoint
- Generate resource classes to organize requests
- Generate DTOs (Data Transfer Objects) for requests and responses
- Handle authentication automatically
- Preserve type safety throughout

### Generated vs Manual Code

**What will be generated:**
- Base connector (`ForgeConnector`)
- All request classes (132 endpoints)
- Resource classes for logical grouping
- Request/Response DTOs from OpenAPI schemas
- Basic authentication setup

**What requires manual work:**
- Laravel service provider integration
- Configuration file (`config/forge-sdk.php`)
- Artisan testing commands
- Custom enum classes for better developer experience
- Enhanced validation logic
- README and documentation
- Test fixtures and mocks
- Any customizations to generated code

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

- **Service Provider**: `ForgeSdkServiceProvider` for package registration
- **Configuration File**: `config/forge-sdk.php` with API token, base URL, timeout, retry, and logging settings
- **Facade**: `Forge` facade for convenient access
- **Atomic Artisan Commands**: Individual command for each API operation (forge:create-server, forge:list-sites, etc.) with confirmation prompts and comprehensive logging
- **Logging**: Package-specific logging channel (configurable, defaults to app's default channel) for all API operations and command executions
- **Minimal Dependencies**: Keep Laravel-specific dependencies minimal for Laravel Zero compatibility

### Testing Strategy

- **Monorepo Testing**: Tests run within Kibble's monorepo Pest setup, not as an independent package
- **HTTP Mocking**: Use Saloon's MockClient for all test requests
- **Fixture Responses**: Create JSON fixtures for all API responses based on Forge API documentation
- **Unit Tests**: Test each request class validates parameters correctly
- **Integration Tests**: Test resource classes compose requests properly
- **Manual Testing Commands**: Artisan commands for real API testing during development
- **No Orchestra Testbench**: Package testing happens in monorepo context, isolated testing handled by monorepo tooling

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

## Implementation Approach (Revised)

### Selected: Generate from OpenAPI + Laravel Packaging

Use Saloon SDK Generator to create the core SDK from the OpenAPI spec, then add Laravel-specific features on top.

**Implementation steps:**
1. Install `saloonphp/saloon-sdk-generator` as a dev dependency
2. Generate SDK from `openapi-spec.json` using the generator's artisan command
3. Review and customize generated code as needed
4. Add Laravel service provider, config, and facade
5. Create artisan testing commands for manual API verification
6. Build comprehensive test suite with mocked responses
7. Write exceptional README documentation

**Pros:**
- Massive time savings - 132 endpoints generated automatically
- Guaranteed API compliance with official OpenAPI spec
- Type-safe DTOs generated from schemas
- Consistent code patterns across all requests
- Easy to regenerate if API changes

**Cons:**
- Generated code may need customization
- Learning curve for generator configuration
- Less control over initial file structure

**Rationale:** With an official OpenAPI spec available, manually writing 132 request classes would be wasteful and error-prone. The generator provides a solid foundation that we can enhance with Laravel-specific features and better developer experience.

## External Dependencies

### Required Dependencies

- **saloonphp/saloon** (^3.0) - Core HTTP client and SDK builder
  - **Justification**: Purpose-built for creating SDKs with excellent architecture patterns, testing support, and developer experience

### Development Dependencies

- **saloonphp/saloon-sdk-generator** (^0.4) - OpenAPI to Saloon SDK generator
  - **Justification**: Automatically generates connector, requests, resources, and DTOs from OpenAPI specification, saving significant development time and ensuring API compliance

- **illuminate/support** (^11.0) - Laravel support package
  - **Justification**: Required for service provider, configuration, and collection helpers. Minimal footprint and compatible with Laravel Zero.

- **illuminate/console** (^11.0) - Laravel console package
  - **Justification**: Required for artisan commands. Compatible with Laravel Zero.

### Development Dependencies

- **pestphp/pest** (^3.0) - Testing framework
  - **Justification**: Already used in monorepo, provides excellent testing DX

- **Note**: Orchestra Testbench is in composer.json for potential future isolated testing, but monorepo tests run in Kibble's Pest context. The monorepo handles package splitting and independent package testing setup.

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
// config/forge-sdk.php
return [
    'api_token' => env('FORGE_API_TOKEN'),
    'base_url' => env('FORGE_API_URL', 'https://forge.laravel.com/api/v1'),
    'timeout' => env('FORGE_TIMEOUT', 30),
    'retry' => [
        'times' => env('FORGE_RETRY_TIMES', 3),
        'sleep' => env('FORGE_RETRY_SLEEP', 1000),
    ],
    'logging' => [
        'channel' => env('FORGE_LOG_CHANNEL', config('logging.default')),
        'level' => env('FORGE_LOG_LEVEL', 'info'),
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

### Atomic Command Structure

Each command should be atomic (single operation) and follow these patterns:

**Read Operations** (list, get, show):
- No confirmation required
- Log the operation at 'info' level
- Display formatted output

**Create Operations**:
- Require confirmation prompt (bypassed with --dangerously-skip-confirmation)
- Log the operation at 'warning' level
- Display created resource details

**Update Operations**:
- Require confirmation prompt (bypassed with --dangerously-skip-confirmation)
- Log the operation at 'warning' level
- Display updated resource details

**Destroy Operations**:
- Require confirmation prompt (bypassed with --dangerously-skip-confirmation)
- Log the operation at 'error' level (for audit trail)
- Display success message

**Action Operations** (reboot, deploy, etc.):
- Require confirmation prompt (bypassed with --dangerously-skip-confirmation)
- Log the operation at 'warning' level
- Display action result

### Command Naming Convention

- `forge:list-{resource}` - List all resources (e.g., forge:list-organizations)
- `forge:get-{resource}` - Get a specific resource (e.g., forge:get-server)
- `forge:create-{resource}` - Create a resource (e.g., forge:create-server)
- `forge:update-{resource}` - Update a resource (e.g., forge:update-site)
- `forge:destroy-{resource}` - Delete a resource (e.g., forge:destroy-database)
- `forge:{action}-{resource}` - Perform action (e.g., forge:reboot-server, forge:deploy-site)

### Resource Identifier Resolution

Commands that operate on specific resources (servers, sites, databases, etc.) should accept **either a name or an ID** for the resource identifier:

**ID-based (programmatic, precise):**
```bash
php artisan forge:reboot-server 1234543343
php artisan forge:deploy-site 987654321
```

**Name-based (human-friendly, CLI-focused):**
```bash
php artisan forge:reboot-server my-staging-server
php artisan forge:deploy-site my-production-app
```

**Resolution logic:**
1. If the identifier is numeric, treat it as an ID and use directly
2. If the identifier is non-numeric, treat it as a name and resolve to ID:
   - Fetch the list of resources (with appropriate parent context if needed)
   - Find the resource matching the name
   - Throw descriptive error if not found or multiple matches exist
   - Use the resolved ID for the API request

**Error handling:**
- Name not found: "Server 'my-staging-server' not found"
- Ambiguous name: "Multiple servers found with name 'staging'. Please use ID instead: 123, 456, 789"
- Invalid ID: "Server with ID '1234543343' not found"

**Applicable to these resource types:**
- Servers (by name)
- Sites (by name, within server context)
- Databases (by name, within server context)
- Organizations (by name)
- Teams (by name, within organization context)
- Background Processes (by command/name, within server context)
- Scheduled Jobs (by command, within server context)
- Firewall Rules (by name/description, within server context)
- SSH Keys (by name, within server context)
- Monitors (by name, within server context)
- Recipes (by name)

**Example implementation pattern:**
```php
protected function resolveServerId(string $identifier): int
{
    // If numeric, assume it's an ID
    if (is_numeric($identifier)) {
        return (int) $identifier;
    }

    // Otherwise, resolve by name
    $servers = $this->forge->servers()->list();
    $matches = $servers->filter(fn($server) => $server->name === $identifier);

    if ($matches->isEmpty()) {
        throw new \InvalidArgumentException("Server '{$identifier}' not found");
    }

    if ($matches->count() > 1) {
        $ids = $matches->pluck('id')->implode(', ');
        throw new \InvalidArgumentException(
            "Multiple servers found with name '{$identifier}'. Please use ID instead: {$ids}"
        );
    }

    return $matches->first()->id;
}
```

### Logging Requirements

All commands must log:
- Command name and parameters (sanitizing sensitive data)
- API request details (endpoint, method)
- API response status
- Success or failure outcome
- Execution time

### Confirmation Prompt Pattern

```php
if ($this->option('dangerously-skip-confirmation')) {
    // Proceed without confirmation
} else {
    if (!$this->confirm('Are you sure you want to destroy server {id}?')) {
        $this->info('Operation cancelled.');
        return 0;
    }
}
```

Example commands:
```bash
# List operations (no confirmation)
php artisan forge:list-organizations
php artisan forge:list-servers --organization=123
php artisan forge:list-servers --organization="my-org"  # Name resolution

# Get operations (no confirmation)
php artisan forge:get-server 456
php artisan forge:get-server my-staging-server  # Name resolution
php artisan forge:get-organization 123
php artisan forge:get-organization "my-org"  # Name resolution

# Create operations (with confirmation)
php artisan forge:create-server \
  --organization=123 \
  --name="my-server" \
  --provider="digitalocean" \
  --size="1gb" \
  --region="nyc3"

# Update operations (with confirmation, accepts name or ID)
php artisan forge:update-server my-staging-server --php-version=php84
php artisan forge:update-server 456 --php-version=php84

# Action operations (with confirmation, accepts name or ID)
php artisan forge:reboot-server my-staging-server
php artisan forge:reboot-server 456
php artisan forge:deploy-site my-production-app
php artisan forge:deploy-site 987654321

# Destroy operations (with confirmation, accepts name or ID)
php artisan forge:destroy-server my-staging-server
php artisan forge:destroy-server 456

# Automated usage (skip confirmation)
php artisan forge:destroy-server 456 --dangerously-skip-confirmation
php artisan forge:destroy-server my-staging-server --dangerously-skip-confirmation
```
