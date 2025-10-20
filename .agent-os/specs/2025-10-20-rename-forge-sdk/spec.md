# Spec Requirements Document

> Spec: Rename forge-sdk Package to forge-client
> Created: 2025-10-20
> Status: Planning

## Overview

Rename the forge-sdk package to forge-client to better reflect its evolved purpose and avoid naming collision with Laravel's potential first-party package. This refactoring includes renaming the package directory, updating all namespace references from ForgeSdk to ForgeClient, and updating the composer.json package name across the monorepo.

## User Stories

### Internal Developer Working with Forge API Integration

As an Artisan Build developer, I want the package name to accurately reflect that it's a comprehensive client library (not just an SDK), so that the package's purpose is clear and there are no conflicts with Laravel's potential first-party forge-sdk package.

When working with the package, developers will use `ForgeClient` namespaces and reference `artisan-build/forge-client` in composer.json, making it immediately clear this is Artisan Build's client implementation that has grown beyond a basic SDK to include console commands, exception handling, and comprehensive resource management.

### External Developer Installing the Package

As an external developer, I want to install a package with a clear, non-conflicting name, so that I can use Artisan Build's Forge client without confusion about whether it's the official Laravel package or a third-party alternative.

The package name `artisan-build/forge-client` clearly indicates this is a third-party client implementation, avoiding the assumption that `forge-sdk` might be Laravel's official package.

## Spec Scope

1. **Directory Rename** - Rename `/packages/forge-sdk` to `/packages/forge-client`
2. **Namespace Updates** - Replace all `ArtisanBuild\ForgeSdk\` namespace references with `ArtisanBuild\ForgeClient\` throughout the package
3. **Composer Configuration** - Update package name from `artisan-build/forge-sdk` to `artisan-build/forge-client` in package composer.json
4. **Monorepo Integration** - Update root composer.json to reference the renamed package in path repositories
5. **Configuration File Rename** - Rename config file from `forge-sdk.php` to `forge-client.php` and update references

## Out of Scope

- Functionality changes or feature additions
- API endpoint modifications
- Documentation beyond what exists in README (separate spec for comprehensive documentation updates)
- Publishing the renamed package to Packagist (will happen automatically via kibble:split)

## Expected Deliverable

1. Package directory successfully renamed from `forge-sdk` to `forge-client`
2. All namespace references updated from `ForgeSdk` to `ForgeClient`
3. All tests passing with `composer ready`
4. Monorepo composer.json correctly references the renamed package
5. Configuration file renamed and all references updated

## Spec Documentation

- Tasks: @.agent-os/specs/2025-10-20-rename-forge-sdk/tasks.md
- Technical Specification: @.agent-os/specs/2025-10-20-rename-forge-sdk/sub-specs/technical-spec.md
- Tests Specification: @.agent-os/specs/2025-10-20-rename-forge-sdk/sub-specs/tests.md
