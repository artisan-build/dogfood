<?php

declare(strict_types=1);

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Process;

it('detects when GitHub CLI is installed', function (): void {
    // Mock composer.json - will be called multiple times by different actions
    File::shouldReceive('get')
        ->with(base_path('composer.json'))
        ->andReturn(json_encode(mockComposerJsonWithAllPackages()));

    // Mock File::isDirectory calls (from EnsureAgentOsIsInstalled)
    File::shouldReceive('isDirectory')
        ->andReturn(true)
        ->zeroOrMoreTimes();

    // Mock File::exists - all config files exist
    File::shouldReceive('exists')
        ->andReturn(true)
        ->zeroOrMoreTimes();

    // Mock pint.json content
    File::shouldReceive('get')
        ->with(base_path('pint.json'))
        ->andReturn(json_encode([
            'preset' => 'laravel',
            'rules' => [
                'declare_strict_types' => true,
                'fully_qualified_strict_types' => true,
                'single_trait_insert_per_statement' => true,
                'array_syntax' => true,
            ],
        ]));

    // Mock phpstan.neon content
    File::shouldReceive('get')
        ->with(base_path('phpstan.neon'))
        ->andReturn('level: 5');

    // Mock rector.php content
    File::shouldReceive('get')
        ->with(base_path('rector.php'))
        ->andReturn('<?php');

    // Allow composer.json to be updated with scripts
    File::shouldReceive('put')
        ->with(base_path('composer.json'), Mockery::type('string'))
        ->zeroOrMoreTimes();

    File::shouldReceive('makeDirectory')
        ->zeroOrMoreTimes();

    File::shouldReceive('copyDirectory')
        ->zeroOrMoreTimes();

    // Mock GitHub CLI check
    // Mock .gitignore content

    File::shouldReceive('get')

        ->with(base_path('.gitignore'))

        ->andReturn(".env\n.phpunit.cache\n");

    File::shouldReceive('append')

        ->with(base_path('.gitignore'), Mockery::type('string'))

        ->zeroOrMoreTimes();

    Process::fake([
        'which gh' => Process::result(output: '/opt/homebrew/bin/gh'),
    ]);

    $this->artisan('agent-os:install')
        ->assertSuccessful();

    Process::assertRan('which gh');
});

it('fails when GitHub CLI is not installed', function (): void {
    // Mock .gitignore content

    File::shouldReceive('get')

        ->with(base_path('.gitignore'))

        ->andReturn(".env\n.phpunit.cache\n");

    File::shouldReceive('append')

        ->with(base_path('.gitignore'), Mockery::type('string'))

        ->zeroOrMoreTimes();

    Process::fake([
        'which gh' => Process::result(exitCode: 1),
    ]);

    $this->artisan('agent-os:install')
        ->assertFailed();

    Process::assertRan('which gh');
});
