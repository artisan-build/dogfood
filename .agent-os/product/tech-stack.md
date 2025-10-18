# Technical Stack

> Last Updated: 2025-10-13
> Version: 1.0.0

## Application Framework

**Framework:** Laravel
**Version:** 11.0+
**Language:** PHP 8.3+

## Database

**Primary:** SQLite (local development)
**Production:** PostgreSQL or MySQL (configurable per deployment)

## Frontend Stack

### Frontend Framework

**Framework:** Livewire
**Version:** 3.0+

### CSS Framework

**Framework:** TailwindCSS
**Version:** 4.0+
**PostCSS:** Yes
**Vite Integration:** Yes

### UI Components

**Library:** Flux UI Pro
**Version:** 2.0+
**Installation:** Via composer (requires license)

## Event Sourcing

**Pattern:** Verbs
**Implementation:** Custom Adverbs package (artisan-build/adverbs)
**State Classes:** TeamState, UserState
**Purpose:** Audit trails, state reconstruction, event replay

## Assets & Media

### Fonts

**Provider:** Default system fonts
**Loading Strategy:** Browser native

### Icons

**Library:** Heroicons (via Flux UI)
**Implementation:** Flux Icon Component

### Profile Photos

**Provider:** UI Avatars API
**Fallback:** Dynamically generated avatar based on user initials

## Code Quality & Testing

### Testing Framework

**Framework:** Pest PHP
**Version:** 3.7+
**Features:** Parallel execution, type coverage, Laravel integration
**Test Database:** SQLite (separate test database)

### Static Analysis

**Tool:** PHPStan
**Level:** 5
**Extensions:** Larastan 3.0+

### Code Style

**Tool:** Laravel Pint
**Version:** 1.13+
**Wrapper:** Tighten Duster 3.2+
**Base Preset:** Laravel with custom rules

### Code Modernization

**Tool:** Rector
**Version:** 2.0+
**Preset:** Laravel-specific rules via driftingly/rector-laravel

### IDE Support

**Tool:** Laravel IDE Helper
**Version:** 3.5+
**Purpose:** PHPDoc generation for models, facades, and meta

## Infrastructure

### Application Hosting

**Platform:** Custom server (SSH deployment)
**Deployment Trigger:** Merge to main branch after CI passes
**Post-Deploy Command:** `php artisan kibble:split`

### Package Distribution

**Primary:** Packagist (public packages)
**Repository Hosting:** GitHub (individual repos per package)
**Splitting Strategy:** Automated via kibble:split command

### Development Tools

**Process Manager:** Concurrently (npm)
**Log Viewer:** Laravel Pail
**Debug Bar:** Laravel Debugbar (dev only)
**Local Environment:** Laravel Herd (recommended)

## Authentication & Authorization

**Package:** Laravel Sanctum
**Version:** 4.0+
**Features:** API tokens, email verification, two-factor authentication support

## Package Management

### Monorepo Structure

**Tool:** Composer path repositories
**Location:** `/packages/*`
**Count:** 22 packages

### Notable Internal Packages

- **adverbs** - Event sourcing utilities
- **agent-os-installer** - Agent OS installation tooling
- **artisan-ui** - UI component library
- **bench** - Benchmarking utilities
- **claudecode** - Claude Code integration
- **code-chat-client** - Code chat functionality
- **docsidian** - Documentation generation
- **fat-enums** - Enhanced enum support
- **flux-themes** - Flux UI theming
- **gh** - GitHub CLI integration
- **hallway-core** - Chat core (deprecated, being removed)
- **hallway-flux** - Chat UI (deprecated, being removed)
- **kibble** - Core package management
- **marketing** - Marketing utilities
- **marketing-mailcoach** - Mailcoach integration
- **mirror** - Code mirroring utilities
- **packagist** - Packagist API integration
- **till** - Subscription management
- **till-stripe** - Stripe integration for Till
- **turbulence** - Testing utilities
- **verbs-flux** - Verbs + Flux UI integration

## Development Workflow

### Local Development

**Command:** `composer dev`
**Services:** Server (8000), Queue Worker, Log Viewer, Vite
**Concurrency:** 4 parallel processes with color-coded output

### Quality Assurance

**Command:** `composer ready`
**Steps:**
1. Clear config cache
2. Generate IDE helper model docs
3. Run Rector (code modernization)
4. Run Duster/Pint (code style)
5. Run PHPStan (static analysis)
6. Run Pest tests

### Testing

**Unit/Feature:** `composer test`
**Parallel:** `composer test-parallel`
**Specific Test:** `composer test -- --filter="TestName"`
**Coverage:** `composer coverage` or `composer coverage-html`

### Package CI Setup

**Script:** `./setup-package-ci-v2.sh`
**Purpose:** Configure GitHub Actions for all packages
**Inputs:** Creates `.github/workflows/tests.yml` and `phpunit.xml.dist` per package

## Version Control

**Platform:** GitHub
**Organization:** artisan-build
**Main Repository:** kibble (monorepo)
**Package Repositories:** Individual repos per package
**Branch Strategy:** Feature branches, PR to main

## Dependencies

### Core Dependencies

- laravel/framework: ^11.0
- laravel/sanctum: ^4.0
- laravel/tinker: ^2.9
- livewire/livewire: ^3.0
- livewire/flux: ^2.0
- livewire/flux-pro: ^2.0
- internachi/modular: ^2.2

### All Internal Packages

Listed via path repositories in composer.json, all versioned as `*` for local development
