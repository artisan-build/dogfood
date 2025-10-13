# Agent OS Installer

A Laravel package for installing Agent OS and related code quality tools into Laravel applications.

## Installation

This package is designed to be used during the initial setup of Agent OS in Laravel projects.

## Requirements

### Minimum Requirements (for target projects)

- PHP 8.2+
- Laravel 11.0+
- Composer 2.0+

### Recommended

- PHP 8.4+
- Laravel 12.0+

## What Gets Installed

The installer will set up the following tools and configurations:

### Code Quality Tools

- **PestPHP** - Modern testing framework (minimum: Pest 3.0)
- **Laravel Pint** - Code formatting and style fixing
- **PHPStan/Larastan** - Static analysis (minimum: level 5, recommended: level 6)
- **Rector** - Automated code refactoring with Laravel rules
- **Tighten Duster** - Unified code quality command (runs Pint, TLINT, and more)

### Development Tools

- **Laravel Debugbar** - Development debugging tool
- **Laravel IDE Helper** - IDE autocompletion for Laravel

### Composer Scripts

The installer adds standardized Composer scripts to your `composer.json`:

- `composer test` - Run tests with config clearing
- `composer test-parallel` - Run tests in parallel
- `composer lint` - Fix code style with Duster
- `composer rector` - Run Rector refactoring
- `composer stan` - Run PHPStan static analysis
- `composer ready` - Run full quality check (rector, lint, stan, test)
- `composer report` - Run quality check with non-blocking failures
- `composer coverage` - Generate test coverage report
- `composer coverage-html` - Generate HTML coverage report
- `composer types` - Check type coverage

**Note:** If existing scripts conflict with these definitions, you'll be prompted to confirm overwriting them. These scripts are required for Agent OS commands to function properly.

### Agent OS Framework

- **Agent OS** - AI-assisted development framework (coming soon)

## License

MIT
