# Spec Requirements Document

> Spec: Laravel Forge SDK
> Created: 2025-10-18
> Status: Planning

## Overview

Build a comprehensive Saloon-based SDK for the Laravel Forge API that covers all available endpoints, provides strong typing with PHP enums for API values, and includes thorough validation to prevent invalid API requests. This package will be Laravel-compatible (including Laravel Zero) with manual testing commands for each endpoint and exceptional documentation.

### API Documentation

**OpenAPI Specification:** The official OpenAPI 3.1.0 specification is located at `@.agent-os/specs/2025-10-18-forge-sdk/openapi-spec.json`. This file contains the complete, authoritative specification for all Laravel Forge API endpoints including request parameters, response schemas, and data types.

**All implementation work must reference this OpenAPI specification** rather than relying on existing knowledge, web searches, or cached information about the Forge API.

## User Stories

### Production Infrastructure Automation

As a SaaS platform developer, I want to programmatically manage Forge infrastructure from my Laravel application, so that I can automate server provisioning, deployments, and management as part of my product's core functionality.

The developer installs the forge-sdk package, configures their Forge API token, and uses atomic artisan commands (forge:create-server, forge:deploy-site, etc.) in production workflows. All destructive operations require confirmation unless bypassed with --dangerously-skip-confirmation. All operations are logged to a configurable channel for auditing and troubleshooting.

### Laravel Application Automating Forge Operations

As a Laravel application developer, I want to programmatically manage Forge resources (servers, sites, databases, etc.), so that I can build custom deployment workflows and management dashboards.

The developer uses the SDK's strongly-typed resource classes and methods to interact with Forge, benefiting from IDE autocomplete, enum validation for API values, and clear error messages when requests fail validation before hitting the API.

### CLI Tool Builder Using Laravel Zero

As a CLI tool builder using Laravel Zero, I want a lightweight Forge SDK without heavy Laravel dependencies, so that I can build command-line tools for Forge management that are fast and portable.

The developer includes the forge-sdk in their Laravel Zero project, configures it minimally, and builds CLI commands that leverage the SDK's clean API without worrying about framework bloat.

## Spec Scope

1. **Complete Endpoint Coverage** - Implement all Laravel Forge API endpoints including authentication, servers, sites, daemons (background processes), workers, commands, SSL certificates, databases, backups, deployment, recipes, integrations (source control providers), organizations, redirect rules, firewall rules, nginx templates, logs, and monitors.

2. **Saloon Architecture** - Structure the SDK following Saloon's best practices for building SDKs with connectors, resources, and request classes.

3. **Strong Typing & Validation** - Create PHP enums for all enumerated API values (server types, sizes, regions, database types, etc.) with validation before API requests.

4. **Laravel Package Integration** - Provide service provider, configuration file, and artisan commands while keeping dependencies minimal for Laravel Zero compatibility.

5. **Production-Ready Atomic Commands** - Build atomic artisan commands for each API operation (forge:create-server, forge:destroy-site, etc.) with proper logging, confirmation prompts, and production-safe defaults for use in automated workflows and manual operations.

6. **Comprehensive Documentation** - Create extensive README.md with table of contents, installation instructions, usage examples for every endpoint, and command documentation.

## Out of Scope

- Framework-agnostic version (focusing on Laravel/Laravel Zero only)
- Admin panel or visual interface (SDK only)
- Forge webhook handling/processing (just webhook management endpoints)
- Real-time integration tests against live Forge API (using mocked responses only)
- Automatic rate limiting/retry logic (Saloon may provide this, but we won't customize it)

## Expected Deliverable

1. **Functional SDK Package** - Complete Saloon-based SDK in `packages/forge-sdk` covering all Forge API endpoints with passing test suite.

2. **Atomic Production Commands** - Individual artisan commands for each API operation (e.g., forge:list-organizations, forge:create-server, forge:destroy-server) with confirmation prompts, comprehensive logging, and --dangerously-skip-confirmation flag for automation. Commands designed for production use in SaaS applications.

3. **Exceptional Documentation** - Comprehensive README.md with table of contents, installation steps, configuration guide, usage examples for all resources, command reference, and troubleshooting section.

## Spec Documentation

- Tasks: @.agent-os/specs/2025-10-18-forge-sdk/tasks.md
- Technical Specification: @.agent-os/specs/2025-10-18-forge-sdk/sub-specs/technical-spec.md
- API Specification: @.agent-os/specs/2025-10-18-forge-sdk/sub-specs/api-spec.md
- Tests Specification: @.agent-os/specs/2025-10-18-forge-sdk/sub-specs/tests.md
