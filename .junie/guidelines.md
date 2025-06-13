# Project Development Guidelines

This project is a monorepo that contains all of our open-source packages. They are split into their own GitHub repositories in CI. It also includes tooling for creating new packages. The long-term vision of this project is to provide all of the following:

1. A monorepo that contains all of our packages (done)
2. Tools to create and manage our packages (work in progress)
3. A Laravel application that contains live demos of all of our open-source packages
4. A documentation portal that contains the documentation for all of our open-source packages.

Keep these four goals in mind as we do our work.

## Build/Configuration Instructions

### Environment Setup

1. **PHP Requirements**: The project requires PHP 8.3 or higher.

2. **Composer Dependencies**: Install dependencies using Composer:
   ```bash
   composer install
   ```

3. **Environment Configuration**: Copy the `.env.example` file to `.env` and configure your environment variables:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database Setup**: The project uses SQLite for testing, but you can configure other database connections in your `.env` file.

5. **Development Server**: We use Laravel herd for local development, so there is no need to start a local development server. The Herd-provided test domain is configured in the .env file.

### Package Structure

The project uses a monorepo structure with custom packages in the `packages/` directory. These packages are loaded via Composer's path repository configuration.

## Testing Information

### Testing Configuration

1. **Test Environment**: Tests use SQLite with an in-memory database by default. Configuration is in `phpunit.xml`.

2. **Test Suites**: The project has three test suites:
   - `Unit`: For unit tests in `tests/Unit/`
   - `Feature`: For feature tests in `tests/Feature/`
   - `Packages`: For tests in the custom packages at `packages/**/tests/`

### Running Tests

1. **Run All Tests**:
   ```bash
   php artisan test
   # or
   composer test
   ```

2. **Run Tests in Parallel**:
   ```bash
   composer test-parallel
   ```

3. **Run Specific Tests**:
   ```bash
   php artisan test tests/Unit/SimpleTest.php
   ```

4. **Run Tests with Coverage**:
   ```bash
   composer coverage
   # or for HTML coverage report
   composer coverage-html
   ```

5. **Type Coverage**:
   ```bash
   composer types
   ```

### Writing Tests

The project uses [Pest PHP](https://pestphp.com/) for testing, which provides a more expressive syntax than traditional PHPUnit.

1. **Basic Test Structure**:
   ```php
   <?php

   it('can perform basic assertions', function (): void {
       expect(true)->toBeTrue();
       expect(1 + 1)->toBe(2);
       expect('hello world')->toContain('world');
   });
   ```

2. **Custom Expectations**: The project includes custom expectations like `toBeIgnoringWhitespace` for comparing strings while ignoring whitespace differences.

3. **Helper Functions**: Use helper functions like `asUser()` for authentication in tests.

4. **Database Testing**: The project uses `LazilyRefreshDatabase` trait for efficient database refreshing between tests.

## Code Quality and Development Practices

### Code Style and Analysis

1. **PHP CS Fixer (Pint)**: The project uses Laravel Pint for code style enforcement:
   ```bash
   composer lint
   ```

2. **Static Analysis (PHPStan)**: The project uses PHPStan with Larastan at level 5:
   ```bash
   composer stan
   ```

3. **Automated Refactoring (Rector)**: The project uses Rector for automated code refactoring:
   ```bash
   composer rector
   ```

4. **Comprehensive Check**: Run all code quality checks and tests:
   ```bash
   composer ready
   ```

### Development Workflow

1. **IDE Helper**: Generate IDE helper files for better autocompletion:
   ```bash
   php artisan ide-helper:models --write
   ```

2. **Debugging**: The project includes Laravel Debugbar and Laravel Pail for debugging.

3. **Frontend**: The project uses Livewire and Flux for frontend components.

### Package Development

The project uses a monorepo structure with custom packages in the `packages/` directory. These packages are loaded via Composer's path repository configuration in `composer.json`:

```json
{
    "repositories": {
        "dogfood": {
            "type": "path",
            "url": "packages/*"
        }
    }
}
```

When developing packages, changes are immediately available to the main application without requiring a separate installation step.

### Important Notes

If comments exist, leave them in place. We often use comments to help guide you on your work and we want them left in place so that we can make sure everything was written as expected. However, please do not add any comments of your own when writing code, including tets.

When starting a new task, make sure the git is clean. If there are any unstaged or uncommitted files, please analyze the changes and make descriptive commits before starting on your assigned task.

When completing a task, run `composer ready` to ensure that all tests and PHPStan checks pass and that all code style is corrected.

When I ask you to commit changes, analyze those changes and create small, related commits with descriptive messages.

Never do any work on the main branch. If I ask you to do something, and we're on the main branch, stop and ask me to either create or checkout the appropriate branch for you to do your work.
