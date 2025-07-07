# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

Kibble is Artisan Build's monorepo Laravel application for developing and managing open-source packages. It's built with Laravel 11, Livewire 3, and Flux UI Pro.

## Essential Commands

### Development
```bash
# Start all development services (server, queue, logs, vite)
composer dev

# Run development server only
composer serve

# Run queue worker
composer queue

# Watch logs
composer logs

# Build frontend assets
npm run build
```

### Code Quality & Testing
```bash
# Run full check (ide-helper, rector, pint, phpstan, tests)
composer ready

# Run tests
composer test

# Run tests in parallel (faster)
composer test-parallel

# Run specific test
composer test -- --filter="test name or class"

# Generate code coverage
composer coverage

# Fix code style
composer pint

# Run static analysis
composer phpstan

# Modernize code
composer rector
```

### Package Development
```bash
# Install/update packages (including local packages)
composer install

# Clear all caches
php artisan optimize:clear

# Generate IDE helpers
php artisan ide-helper:generate
php artisan ide-helper:models -W
```

## Architecture & Key Patterns

### Event Sourcing with Verbs
The application heavily uses the Verbs event sourcing pattern. Events are stored and replayed to rebuild application state. Key locations:
- Events: `app/Events/` - Define state changes
- States: `app/States/` - Aggregate states rebuilt from events
- Event handlers use `->fired()` and `->committed()` hooks

### Package Structure
Each package in `/packages/` follows this structure:
- `src/` - Main source code
- `tests/` - Pest tests
- `composer.json` - Package dependencies
- `README.md` - Package documentation

Packages are loaded via Composer path repositories defined in the root `composer.json`.

### Team-Based Architecture
- Teams are the primary organizational unit
- Users belong to teams via invitations
- Most models are scoped to teams
- Team functionality in `app/Models/Team.php` and `packages/till/`

### UI Components with Flux
- Flux UI Pro components are used throughout
- Custom Blade components in `resources/views/components/`
- Livewire components in `app/Livewire/`
- Theme customization via `packages/flux-themes/`

## Testing Approach

### Pest PHP Configuration
- Tests use Pest with Laravel integration
- Parallel testing enabled with multiple SQLite databases
- Test files follow `*Test.php` naming convention
- Use `test()` or `it()` syntax, not PHPUnit classes

### Test Database Setup
```bash
# Create test database
touch database/testing.sqlite

# Run migrations for test
php artisan migrate --env=testing
```

## Code Style Guidelines

### Laravel Pint Configuration
The project uses Laravel Pint with custom rules:
- PSR-12 base with Laravel preset
- Ordered imports by length
- No unused imports
- Array syntax: short form `[]`
- Trailing commas in multiline arrays

### PHPStan Level 5
Static analysis is configured at level 5. Common patterns:
- Use proper type hints and return types
- Avoid mixed types where possible
- Document complex types with PHPDoc

## Important Patterns

### Service Providers
Custom functionality is registered in service providers:
- Package providers in each package's `src/` directory
- App providers in `app/Providers/`

### Livewire Components
- Located in `app/Livewire/`
- Use computed properties for reactive data
- Follow single responsibility principle

### Blade Components
- Anonymous components in `resources/views/components/`
- Class-based components in `app/View/Components/`
- Flux UI components available globally

## Environment Requirements

- PHP 8.3+
- Node.js 18+
- SQLite for local development
- Flux UI Pro license (required for UI components)

## Common Troubleshooting

### Package Not Found
If a local package isn't recognized:
1. Check it's listed in root `composer.json` repositories
2. Run `composer install`
3. Clear Composer cache: `composer clear-cache`

### Vite Build Issues
1. Ensure Node modules installed: `npm install`
2. Clear Vite cache: `rm -rf node_modules/.vite`
3. Restart dev server: `npm run dev`

### Test Failures
1. Check test database exists: `database/testing.sqlite`
2. Run migrations: `php artisan migrate --env=testing`
3. Clear test cache: `php artisan optimize:clear --env=testing`