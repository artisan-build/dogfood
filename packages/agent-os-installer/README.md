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

- **Agent OS** - AI-assisted development framework from [Builder Methods](https://buildermethods.com/)

We install our own opinionated fork of Agent OS, which includes a Laravel profile. This fork is available on [GitHub](https://github.com/artisan-build/agent-os). If you already have Agent OS installed, we will simply copy our Laravel profile into your existing Agent OS installation. If you already have a profile called Laravel, we will assume you have things set up the way you want them and completely skip the Agent OS installation portion of this.

The reason that we install all of those code quality tools is that in our Laravel profile, the instructions mention things like `composer report` and `composer ready`, which are scripts that run all this tooling to ensure that everything is truly up to our specifications.

## Agent OS Web Viewer

This package includes a web viewer for browsing Agent OS documentation directly in your Laravel application. The viewer provides a GitHub-style interface for viewing your product documentation, specs, and other Agent OS files.

### Features

- **GitHub-Flavored Markdown Rendering** - Full GFM support with syntax highlighting
- **Dark Mode Support** - Automatic theme switching based on system preference
- **Product Documentation View** - Concatenated view of `.agent-os/product/` folder
- **Spec Viewer** - Unified view of specs with automatic concatenation of spec.md, sub-specs, and tasks
- **Smart Internal Links** - `@.agent-os/...` references automatically convert to anchor links for same-page navigation
- **Sidebar Navigation** - Browse all specs, product docs, and README
- **Access Control** - Configurable middleware with gate support

### Configuration

The viewer can be configured via `config/agent-os-installer.php`:

```php
'viewer' => [
    'enabled' => env('AGENT_OS_VIEWER_ENABLED', true),
    'route_prefix' => env('AGENT_OS_ROUTE_PREFIX', 'agent-os'),
    'middleware' => ['web'],
    'gate' => null, // Optional gate name for authorization
    'paths' => [], // Additional documentation paths to include
    'default_view' => 'product', // 'product' or 'readme'
],
```

### Environment Variables

- `AGENT_OS_VIEWER_ENABLED` - Enable/disable the viewer (default: `true`)
- `AGENT_OS_ROUTE_PREFIX` - Route prefix for the viewer (default: `agent-os`)

### Access Control

By default, the viewer is accessible in local environments and requires authentication in production. You can customize this by:

1. **Custom Gate**: Set a gate name in the config:

```php
'viewer' => [
    'gate' => 'view-agent-os-docs',
],
```

Then define the gate in your `AuthServiceProvider`:

```php
Gate::define('view-agent-os-docs', function ($user) {
    return $user->is_admin;
});
```

2. **Custom Middleware**: Add your own middleware to the config:

```php
'viewer' => [
    'middleware' => ['web', 'auth', 'can:view-docs'],
],
```

### Usage

Once installed, visit `/agent-os` (or your configured route prefix) in your browser to view the documentation.

### Internal Reference Links

The viewer supports Agent OS reference links using the `@` prefix:

```markdown
See @.agent-os/product/mission.md for more details.
Review @.agent-os/specs/2025-11-01-my-spec/sub-specs/technical-spec.md
Check @.agent-os/specs/2025-11-01-my-spec/tasks.md for task list
```

Within the same spec, these automatically become anchor links for smooth in-page navigation.

## License

MIT
