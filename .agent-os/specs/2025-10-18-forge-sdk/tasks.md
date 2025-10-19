# Spec Tasks

These are the tasks to be completed for the spec detailed in @.agent-os/specs/2025-10-18-forge-sdk/spec.md

> Created: 2025-10-18
> Updated: 2025-10-19
> Status: Ready for Implementation
> Strategy: OpenAPI Generation + Laravel Integration

## Overview

This SDK will be generated from the official Laravel Forge OpenAPI specification (`openapi-spec.json`) using the Saloon SDK Generator. The generator will create connectors, requests, resources, and DTOs automatically. Our focus is on Laravel integration, testing infrastructure, and exceptional documentation.

## Tasks

- [x] 1. Package Foundation & SDK Generation
  - [x] 1.1 Write tests for package configuration
  - [x] 1.2 Install saloonphp/saloon and saloonphp/saloon-sdk-generator dependencies
  - [x] 1.3 Create config/forge-sdk.php with API token, base URL, timeout, retry configuration
  - [x] 1.4 Generate SDK from openapi-spec.json using SDK generator
  - [x] 1.5 Review generated connector, requests, resources, and DTOs
  - [x] 1.6 Customize generated code if needed (namespaces, class names, etc.)
  - [x] 1.7 Verify generated connector has correct base URL and authentication
  - [x] 1.8 Run `composer ready` to ensure generated code passes all quality checks

- [x] 2. Laravel Service Provider Integration
  - [x] 2.1 Write tests for service provider registration
  - [x] 2.2 Create ForgeServiceProvider extending Illuminate\Support\ServiceProvider
  - [x] 2.3 Register connector as singleton in container
  - [x] 2.4 Publish config file in boot() method
  - [x] 2.5 Create Forge facade for convenient access
  - [x] 2.6 Add auto-discovery configuration to composer.json
  - [x] 2.7 Verify service provider tests pass

- [x] 3. Testing Infrastructure
  - [x] 3.1 Create test fixtures directory structure (tests/Fixtures/Responses/)
  - [x] 3.2 Create example JSON mock responses for Organizations
  - [x] 3.3 Create example JSON mock responses for Servers
  - [x] 3.4 Create MockResponseFactory helper for loading fixtures in tests
  - [x] 3.5 Write example tests for Organizations resource (list, show, error handling)
  - [x] 3.6 Write example tests for Servers resource (list, show, delete, error handling)
  - [x] 3.7 Verify all infrastructure tests pass

- [x] 4. Atomic Commands - Organizations & Servers (with Logging)
  - [x] 4.1 Update config/forge-sdk.php to include logging configuration (channel, level)
  - [x] 4.2 Write tests for organization commands
  - [x] 4.3 Create forge:list-organizations command (no confirmation, info logging)
  - [x] 4.4 Create forge:get-organization command (no confirmation, info logging)
  - [x] 4.5 Write tests for server commands
  - [x] 4.6 Create forge:list-servers command (no confirmation, info logging)
  - [x] 4.7 Create forge:get-server command (no confirmation, info logging)
  - [x] 4.8 Create forge:create-server command (with confirmation, warning logging)
  - [x] 4.9 Skipped update-server (no general update endpoint in API)
  - [x] 4.10 Create forge:destroy-server command (with confirmation, error logging for audit)
  - [x] 4.11 Create forge:reboot-server command (with confirmation, warning logging)
  - [x] 4.12 Add --dangerously-skip-confirmation option to all destructive commands
  - [x] 4.13 Implement comprehensive logging for all commands (operation, params, response, timing)
  - [x] 4.14 Register all commands in service provider
  - [x] 4.15 Verify all command tests pass

- [x] 5. Atomic Commands - Sites & Deployments
  - [x] 5.1 Write tests for site commands
  - [x] 5.2 Create atomic site commands (list, get, create, update, destroy with confirmations and logging)
  - [x] 5.3 Create site action commands (deploy, enable-quick-deploy, disable-quick-deploy)
  - [x] 5.4 Write tests for deployment commands
  - [x] 5.5 Create deployment commands (list, get, trigger, update-script with confirmations and logging)
  - [x] 5.6 Verify all site and deployment command tests pass

- [x] 6. Atomic Commands - Additional Resources
  - [x] 6.1 Write tests for database commands
  - [x] 6.2 Create database schema commands (list, get, create, destroy with confirmations)
  - [x] 6.3 Create database user commands (list, get, create, update, destroy with confirmations)
  - [x] 6.4 Write tests for background process commands
  - [x] 6.5 Create background process commands (list, get, create, update, destroy, restart with confirmations)
  - [x] 6.6 Write tests for firewall rule commands
  - [x] 6.7 Create firewall commands (list, get, create, destroy with confirmations)
  - [x] 6.8 Write tests for SSL certificate commands
  - [x] 6.9 Create SSL commands (list, get, create, activate, destroy with confirmations)
  - [x] 6.10 Verify all resource command tests pass

- [x] 7. Enhanced Enums for Developer Experience
  - [x] 7.1 Write tests for enum classes (already existed, verified passing)
  - [x] 7.2 Review generated DTOs for enum fields (DTOs use simple arrays, enums used in commands)
  - [x] 7.3 CloudProvider enum already existed with all cases
  - [x] 7.4 Skipped ServerSize enums (provider/region-specific, fetched dynamically via API)
  - [x] 7.5 PhpVersion enum already existed, added helper methods
  - [x] 7.6 DatabaseType enum already existed, added helper methods
  - [x] 7.7 Added helper methods to all enums (label, description, validation helpers)
  - [x] 7.8 Created UbuntuVersion enum (22.04, 24.04) with helper methods
  - [x] 7.9 All enum tests pass (132 tests, 381 assertions)

- [x] 8. Comprehensive Test Coverage
  - [x] 8.1 Write integration tests for all Organization endpoints (already existed from Task 3)
  - [x] 8.2 Write integration tests for all Server endpoints (already existed from Task 3)
  - [x] 8.3 Write integration tests for all Site endpoints
  - [x] 8.4 Write integration tests for all Deployment endpoints
  - [x] 8.5 Write integration tests for Database endpoints (schemas and users)
  - [x] 8.6 Write integration tests for Background Process endpoints
  - [x] 8.7 Write integration tests for Command endpoints
  - [x] 8.8 Write integration tests for Firewall Rule endpoints
  - [x] 8.9 Skip SSL Certificate integration tests (command tests already cover this)
  - [x] 8.10 Write integration tests for remaining resource endpoints (Scheduled Jobs, Recipes)
  - [x] 8.11 Created comprehensive mock fixtures for all tested resources
  - [x] 8.12 Fixed all parameter signature issues (removed incorrect filtercommand from BackgroundProcesses)
  - [x] 8.13 All 133 forge-sdk tests pass successfully (100% pass rate achieved)

- [ ] 9. Exception Handling & Error Messages
  - [ ] 9.1 Write tests for custom exception classes
  - [ ] 9.2 Create ForgeException base exception
  - [ ] 9.3 Create ValidationException for parameter validation
  - [ ] 9.4 Create ApiException for API errors
  - [ ] 9.5 Create RateLimitException for 429 responses
  - [ ] 9.6 Create AuthenticationException for 401/403 responses
  - [ ] 9.7 Add exception handling middleware to connector
  - [ ] 9.8 Add helpful error messages with context
  - [ ] 9.9 Verify all exception tests pass

- [ ] 10. README Documentation
  - [ ] 10.1 Create README.md with comprehensive table of contents
  - [ ] 10.2 Write Installation section (composer require, publish config)
  - [ ] 10.3 Write Configuration section (API token, environment variables)
  - [ ] 10.4 Write Quick Start example (basic usage pattern)
  - [ ] 10.5 Document Organizations resource with all endpoint examples
  - [ ] 10.6 Document Servers resource with create, manage, action examples
  - [ ] 10.7 Document Sites resource with Git and environment examples
  - [ ] 10.8 Document Deployments resource with trigger and monitoring examples
  - [ ] 10.9 Document all remaining resources with usage examples
  - [ ] 10.10 Add Testing Commands reference section
  - [ ] 10.11 Add Error Handling & Exceptions section
  - [ ] 10.12 Add Troubleshooting section (common issues, rate limits)
  - [ ] 10.13 Add Contributing guide
  - [ ] 10.14 Add License (MIT)
  - [ ] 10.15 Review README for completeness and clarity

- [ ] 11. Final Quality Checks & Polish
  - [ ] 11.1 Run `composer ready` and fix any issues
  - [ ] 11.2 Run `composer test` and ensure 100% pass rate
  - [ ] 11.3 Review all generated code for consistency
  - [ ] 11.4 Add PHPDoc blocks to all public methods
  - [ ] 11.5 Check that all config values have sensible defaults
  - [ ] 11.6 Verify Laravel Zero compatibility (minimal dependencies)
  - [ ] 11.7 Test package in a fresh Laravel application
  - [ ] 11.8 Test package in a Laravel Zero application
  - [ ] 11.9 Verify all artisan commands work correctly
  - [ ] 11.10 Final documentation review
  - [ ] 11.11 Create CHANGELOG.md with v1.0.0 release notes
  - [ ] 11.12 Tag v1.0.0 release

## Notes

- Tasks 1-3 focus on generating the core SDK and setting up testing infrastructure
- Tasks 4-6 provide atomic, production-ready commands with logging and confirmation prompts
- All commands designed for use in production SaaS applications that automate Forge infrastructure
- Commands include --dangerously-skip-confirmation flag for automated workflows
- Comprehensive logging to configurable channel enables audit trails and troubleshooting
- Task 7 enhances generated code with better enum classes
- Task 8 ensures comprehensive test coverage using mocked responses
- Task 9 adds exception handling for better error messages
- Task 10 creates exceptional documentation (including command reference)
- Task 11 ensures production-ready quality

The SDK generation approach drastically reduces implementation time while ensuring API compliance. Our effort focuses on Laravel integration, production-ready atomic commands with comprehensive logging, testing, and developer experience rather than manually writing 132 request classes.
