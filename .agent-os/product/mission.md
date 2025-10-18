# Product Mission

> Last Updated: 2025-10-13
> Version: 1.0.0

## Pitch

Kibble is a Laravel monorepo application that helps Artisan Build's team develop and manage their suite of open-source PHP packages by providing a unified development environment where all packages can be worked on together before being automatically split to individual GitHub repositories.

## Users

### Primary Customers

- **Artisan Build Internal Team**: Core developers who create and maintain the package ecosystem
- **Open-Source Contributors**: External developers who want to contribute improvements to Artisan Build packages (though contributions are expected to be minimal due to opinionated nature)

### User Personas

**Internal Package Developer** (25-45 years old)
- **Role:** Software Engineer / Package Maintainer
- **Context:** Works on multiple related packages that share dependencies and architectural patterns
- **Pain Points:** Managing changes across multiple package repositories, ensuring consistency, coordinating releases
- **Goals:** Develop packages efficiently, maintain code quality, deploy updates seamlessly

**External Contributor** (20-50 years old)
- **Role:** Open-Source Developer
- **Context:** Wants to contribute bug fixes or improvements to specific Artisan Build packages
- **Pain Points:** Unfamiliar with monorepo workflow, unclear how to contribute to packages hosted in separate repos
- **Goals:** Submit quality contributions, understand the contribution workflow

## The Problem

### Fragmented Package Development

Managing 22+ separate package repositories creates overhead in coordinating changes, maintaining consistency, and ensuring dependencies work together. This results in slower development cycles and increased risk of breaking changes.

**Our Solution:** Consolidate all package development in a single monorepo where cross-package changes can be tested together, then automatically split to individual repositories for distribution.

### Inconsistent Development Standards

With multiple packages, maintaining consistent code style, testing practices, and architectural patterns becomes challenging. This leads to technical debt and reduced code quality.

**Our Solution:** Enforce unified code quality standards through centralized tooling (Pint, PHPStan, Rector, Pest) that runs across all packages with `composer ready`.

### Complex Contribution Workflow

Contributors finding packages on GitHub don't understand they need to work in the monorepo, leading to wasted effort on PRs submitted to the wrong repository.

**Our Solution:** Document clear contribution guidelines in package READMEs and implement auto-close on individual package repos with instructions to contribute through Kibble.

## Differentiators

### Monorepo with Automatic Package Splitting

Unlike traditional package development where each package lives in isolation, Kibble allows development in a cohesive environment while maintaining separate GitHub repositories for distribution. This provides the best of both worlds: unified development experience with standard package distribution.

### Event Sourcing Architecture

Unlike typical Laravel applications, Kibble uses the Verbs event sourcing pattern through our custom Adverbs package. This provides audit trails, state reconstruction, and temporal query capabilities that standard Eloquent applications lack.

### Opinionated Package Ecosystem

Unlike general-purpose package collections, Kibble's packages are designed for Artisan Build's specific development philosophy and workflow. This tight integration results in packages that work seamlessly together for teams that share our architectural preferences.

## Key Features

### Core Features

- **Monorepo Package Management:** Develop 22+ packages in a unified codebase with shared dependencies and testing
- **Automatic Package Splitting:** `php artisan kibble:split` command deploys changes to individual GitHub repositories
- **Event Sourcing with Verbs:** State management through events using custom Adverbs package for TeamState and UserState
- **Team-Based Access Control:** Multi-tenancy with team ownership, memberships, and role-based permissions
- **Comprehensive Testing Suite:** Pest PHP with parallel test execution across all packages
- **Unified Code Quality:** Single `composer ready` command runs IDE helpers, Rector, Pint, PHPStan, and tests

### Development Features

- **Package CI Automation:** `./setup-package-ci-v2.sh` script configures GitHub Actions for all packages
- **Local Package Development:** Composer path repositories enable real-time package updates during development
- **Multi-Process Dev Server:** `composer dev` runs server, queue, logs, and Vite concurrently
- **Individual Package Testing:** Each package supports both monorepo and isolated testing contexts

### Infrastructure Features

- **Flux UI Pro Integration:** Modern, accessible UI components throughout the application
- **Livewire 3 Architecture:** Reactive components with minimal JavaScript
- **SQLite Development Database:** Fast, zero-configuration local development
- **Laravel 11 Foundation:** Latest framework features and performance improvements
