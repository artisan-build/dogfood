# Spec Requirements Document

> Spec: OpenCode SDK Generation
> Created: 2025-10-26
> Status: Planning

## Overview

Generate a complete Saloon SDK for the OpenCode API within the existing `packages/opencode-sdk` package structure, enabling Laravel applications to interact with OpenCode programmatically through a clean, type-safe PHP interface.

## User Stories

### Laravel Developer Integrating OpenCode

As a Laravel developer, I want to interact with the OpenCode API from my application, so that I can programmatically create sessions, send prompts, and retrieve AI assistant responses.

The developer installs `artisan-build/opencode-sdk` via Composer, configures the base URL and any authentication in the config file, and uses the generated SDK classes to interact with OpenCode. For example: creating a new session, sending a message to that session, and retrieving the assistant's response - all through clean, type-safe PHP methods with IDE autocompletion.

### Package Maintainer Adding OpenCode Support

As a package maintainer, I want a well-structured SDK that follows Laravel conventions, so that I can easily add OpenCode functionality to my package without writing manual HTTP requests.

The maintainer requires the SDK in their `composer.json`, accesses OpenCode features through the generated connector and resource classes, and benefits from the automatic JSON serialization/deserialization, error handling, and retry logic that Saloon provides.

## Spec Scope

1. **Saloon SDK Generation** - Run the Saloon SDK generator against the OpenAPI spec to create connector, resources, and request classes
2. **Configuration Setup** - Create Laravel config file with base URL, timeout, and optional authentication settings
3. **Service Provider Integration** - Update the service provider to bind the SDK connector to Laravel's container
4. **Generated Code Organization** - Structure the generated SDK code in `src/Generated/` following PSR-4 conventions
5. **README Documentation** - Update package README with installation instructions, configuration examples, and basic usage

## Out of Scope

- Custom wrapper methods around generated SDK (will use Saloon's generated code directly)
- Advanced retry logic or circuit breakers (use Saloon's built-in features)
- Websocket/SSE streaming support (focus on standard HTTP requests first)
- CLI commands for interacting with OpenCode (SDK library only)

## Expected Deliverable

1. SDK successfully generated from the OpenAPI spec at `packages/opencode-sdk/swagger/2025-10-26.json`
2. Generated SDK classes organized in `packages/opencode-sdk/src/Generated/`
3. Configuration file at `packages/opencode-sdk/config/opencode-sdk.php` with base URL and auth options
4. Service provider properly binds the OpenCode connector for dependency injection
5. README updated with clear installation, configuration, and usage examples
6. All tests passing with `composer test` from monorepo root

## Spec Documentation

- Tasks: @.agent-os/specs/2025-10-26-opencode-sdk-generation/tasks.md
- Technical Specification: @.agent-os/specs/2025-10-26-opencode-sdk-generation/sub-specs/technical-spec.md
- Tests Specification: @.agent-os/specs/2025-10-26-opencode-sdk-generation/sub-specs/tests.md
