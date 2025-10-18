# Spec Requirements Document

> Spec: Laravel Forge SDK
> Created: 2025-10-18
> Status: Planning

## Overview

Build a comprehensive Saloon-based SDK for the Laravel Forge API that covers all available endpoints, provides strong typing with PHP enums for API values, and includes thorough validation to prevent invalid API requests. This package will be Laravel-compatible (including Laravel Zero) with manual testing commands for each endpoint and exceptional documentation.

## User Stories

### Package Developer Testing Forge Integration

As a package developer, I want to test my application's integration with Laravel Forge API endpoints, so that I can verify my deployment automation works correctly before pushing to production.

The developer installs the forge-sdk package, configures their Forge API token, and uses the provided artisan commands to test individual endpoints (creating servers, deploying sites, managing SSL certificates, etc.) with real API calls while developing their automation scripts.

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

5. **Manual Testing Commands** - Build artisan commands for each endpoint to enable manual API testing during development and troubleshooting.

6. **Comprehensive Documentation** - Create extensive README.md with table of contents, installation instructions, usage examples for every endpoint, and command documentation.

## Out of Scope

- Framework-agnostic version (focusing on Laravel/Laravel Zero only)
- Admin panel or visual interface (SDK only)
- Forge webhook handling/processing (just webhook management endpoints)
- Real-time integration tests against live Forge API (using mocked responses only)
- Automatic rate limiting/retry logic (Saloon may provide this, but we won't customize it)

## Expected Deliverable

1. **Functional SDK Package** - Complete Saloon-based SDK in `packages/forge-sdk` covering all Forge API endpoints with passing test suite.

2. **Testing Commands** - Artisan command for each major endpoint group that accepts parameters and makes real API calls for manual verification.

3. **Exceptional Documentation** - Comprehensive README.md with table of contents, installation steps, configuration guide, usage examples for all resources, command reference, and troubleshooting section.
