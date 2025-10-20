<?php

declare(strict_types=1);

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Process;

beforeEach(function (): void {
    Process::preventStrayProcesses();

    // Mock GitHub CLI check
    Process::fake([
        'which gh' => Process::result(output: '/opt/homebrew/bin/gh'),
        '*/agent-os/scripts/project-install.sh*' => Process::result(),
    ]);

    // Mock File::isDirectory calls (from EnsureAgentOsIsInstalled)
    File::shouldReceive('isDirectory')
        ->andReturn(true)
        ->zeroOrMoreTimes();

    // Allow File::put for config files (pint.json, phpstan.neon, rector.php) and composer.json updates
    File::shouldReceive('put')
        ->withArgs(fn ($path) => str_contains((string) $path, 'pint.json') ||
               str_contains((string) $path, 'phpstan.neon') ||
               str_contains((string) $path, 'rector.php') ||
               str_contains((string) $path, 'composer.json'))
        ->zeroOrMoreTimes();
});

it('detects when all scripts are properly defined', function (): void {
    $packages = mockComposerJsonWithAllPackages();
    $packages['scripts'] = [
        'test' => [
            '@php artisan config:clear --ansi',
            '@php artisan test',
        ],
        'test-parallel' => [
            '@php artisan config:clear --ansi',
            '@php artisan test --parallel --recreate-databases',
        ],
        'lint' => [
            'vendor/bin/duster fix --using=tlint,php-cs-fixer,pint',
        ],
        'rector' => [
            'vendor/bin/rector',
        ],
        'stan' => [
            'vendor/bin/phpstan analyse --memory-limit=512M',
        ],
        'sniff' => [
            'vendor/bin/phpcs',
        ],
        'sniff-fix' => [
            'vendor/bin/phpcbf',
        ],
        'ready' => [
            '@php artisan config:clear --ansi',
            '@php artisan ide-helper:models --write',
            'composer rector',
            'composer lint',
            'composer stan',
            'composer sniff',
            'composer test',
        ],
        'report' => [
            '@php artisan config:clear --ansi || true',
            '@php artisan ide-helper:models --write || true',
            'composer rector || true',
            'composer lint || true',
            'composer stan || true',
            'composer sniff || true',
            'composer test || true',
        ],
        'coverage-html' => [
            'XDEBUG_MODE=coverage herd debug ./vendor/bin/pest --coverage-php coverage.php',
            '@php artisan generate-code-coverage-html',
        ],
        'coverage' => [
            'XDEBUG_MODE=coverage herd debug ./vendor/bin/pest --coverage',
        ],
        'types' => [
            'vendor/bin/pest --type-coverage',
        ],
    ];

    File::shouldReceive('get')
        ->with(base_path('composer.json'))
        ->andReturn(json_encode($packages));

    File::shouldReceive('exists')->andReturn(true);
    File::shouldReceive('get')->with(base_path('pint.json'))->andReturn('{}');
    File::shouldReceive('get')->with(base_path('phpstan.neon'))->andReturn('level: 6');
    File::shouldReceive('get')->with(base_path('rector.php'))->andReturn('<?php');
    File::shouldReceive('get')->with(base_path('.gitignore'))->andReturn(".env\n.phpunit.cache\n");

    File::shouldReceive('makeDirectory')->zeroOrMoreTimes();
    File::shouldReceive('copyDirectory')->zeroOrMoreTimes();
    File::shouldReceive('append')->with(base_path('.gitignore'), Mockery::type('string'))->zeroOrMoreTimes();

    $this->artisan('agent-os:install')
        ->assertSuccessful();
});

it('adds missing scripts', function (): void {
    $packages = mockComposerJsonWithAllPackages();
    $packages['scripts'] = [
        'test' => [
            '@php artisan config:clear --ansi',
            '@php artisan test',
        ],
    ];

    File::shouldReceive('get')
        ->with(base_path('composer.json'))
        ->andReturn(json_encode($packages));

    File::shouldReceive('exists')->andReturn(true);
    File::shouldReceive('get')->with(base_path('pint.json'))->andReturn('{}');
    File::shouldReceive('get')->with(base_path('phpstan.neon'))->andReturn('level: 6');
    File::shouldReceive('get')->with(base_path('rector.php'))->andReturn('<?php');
    File::shouldReceive('get')->with(base_path('.gitignore'))->andReturn(".env\n.phpunit.cache\n");

    File::shouldReceive('makeDirectory')->zeroOrMoreTimes();
    File::shouldReceive('copyDirectory')->zeroOrMoreTimes();
    File::shouldReceive('append')->with(base_path('.gitignore'), Mockery::type('string'))->zeroOrMoreTimes();

    $this->artisan('agent-os:install')
        ->assertSuccessful();
});

it('prompts for confirmation when scripts conflict', function (): void {
    $packages = mockComposerJsonWithAllPackages();
    $packages['scripts'] = [
        'test' => [
            'phpunit', // Different from what we need
        ],
        'lint' => [
            'php-cs-fixer fix', // Different from what we need
        ],
    ];

    File::shouldReceive('get')
        ->with(base_path('composer.json'))
        ->andReturn(json_encode($packages));

    File::shouldReceive('exists')->andReturn(true);
    File::shouldReceive('get')->with(base_path('pint.json'))->andReturn('{}');
    File::shouldReceive('get')->with(base_path('phpstan.neon'))->andReturn('level: 6');
    File::shouldReceive('get')->with(base_path('rector.php'))->andReturn('<?php');
    File::shouldReceive('get')->with(base_path('.gitignore'))->andReturn(".env\n.phpunit.cache\n");

    File::shouldReceive('makeDirectory')->zeroOrMoreTimes();
    File::shouldReceive('copyDirectory')->zeroOrMoreTimes();
    File::shouldReceive('append')->with(base_path('.gitignore'), Mockery::type('string'))->zeroOrMoreTimes();

    // Simulate user declining to overwrite
    $this->artisan('agent-os:install')
        ->expectsConfirmation('Would you like to overwrite these scripts with Agent OS optimized versions?', 'no')
        ->assertFailed();
});

it('overwrites scripts when user confirms', function (): void {
    $packages = mockComposerJsonWithAllPackages();
    $packages['scripts'] = [
        'test' => [
            'phpunit',
        ],
    ];

    File::shouldReceive('get')
        ->with(base_path('composer.json'))
        ->andReturn(json_encode($packages));

    File::shouldReceive('exists')->andReturn(true);
    File::shouldReceive('get')->with(base_path('pint.json'))->andReturn('{}');
    File::shouldReceive('get')->with(base_path('phpstan.neon'))->andReturn('level: 6');
    File::shouldReceive('get')->with(base_path('rector.php'))->andReturn('<?php');
    File::shouldReceive('get')->with(base_path('.gitignore'))->andReturn(".env\n.phpunit.cache\n");

    File::shouldReceive('makeDirectory')->zeroOrMoreTimes();
    File::shouldReceive('copyDirectory')->zeroOrMoreTimes();
    File::shouldReceive('append')->with(base_path('.gitignore'), Mockery::type('string'))->zeroOrMoreTimes();

    $this->artisan('agent-os:install')
        ->expectsConfirmation('Would you like to overwrite these scripts with Agent OS optimized versions?', 'yes')
        ->assertSuccessful();
});
