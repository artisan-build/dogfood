<?php

declare(strict_types=1);

namespace ArtisanBuild\AgentOsInstaller\Actions;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Process;

/**
 * Ensure Enlightn is installed and configured
 *
 * Minimum Requirements:
 * - PHP 8.2+
 * - Laravel 11.0+
 * - Composer 2.0+
 */
class EnsureEnlightnIsInstalled
{
    /**
     * Execute the action to ensure Enlightn is installed
     */
    public function __invoke(Command $command): bool
    {
        if (! $this->isEnlightnInstalled()) {
            $command->info('Installing Enlightn...');

            if (! $this->installEnlightn($command)) {
                $command->error('Failed to install Enlightn');

                return false;
            }

            $command->info('✓ Enlightn installed successfully');
        } else {
            $command->info('✓ Enlightn is already installed');
        }

        // Ensure config/enlightn.php configuration
        $this->ensureEnlightnConfiguration($command);

        return true;
    }

    /**
     * Default Enlightn configuration
     */
    protected function getDefaultConfig(): string
    {
        return <<<'PHP_WRAP'
        <?php

        return [

            /*
            |--------------------------------------------------------------------------
            | Enlightn Analyzer Classes
            |--------------------------------------------------------------------------
            |
            | The following array lists the "analyzer" classes that will be registered
            | with Enlightn. These analyzers run an analysis on the application via
            | various methods such as static analysis. Feel free to customize it.
            |
            */
            'analyzers' => ['*'],

            // If you wish to skip running some analyzers, list the classes in the array below.
            'exclude_analyzers' => [],

            // If you wish to skip running some analyzers in CI mode, list the classes below.
            'ci_mode_exclude_analyzers' => [],

            /*
            |--------------------------------------------------------------------------
            | Enlightn Analyzer Paths
            |--------------------------------------------------------------------------
            |
            | The following array lists the "analyzer" paths that will be searched
            | recursively to find analyzer classes. This option will only be used
            | if the analyzers option above is set to the asterisk wildcard. The
            | key is the base namespace to resolve the class name.
            |
            */
            'analyzer_paths' => [
                'Enlightn\\Enlightn\\Analyzers' => base_path('vendor/ivqonsanada/enlightn/src/Analyzers'),
                'Enlightn\\EnlightnPro\\Analyzers' => base_path('vendor/enlightn/enlightnpro/src/Analyzers'),
            ],

            /*
            |--------------------------------------------------------------------------
            | Enlightn Base Path
            |--------------------------------------------------------------------------
            |
            | The following array lists the directories that will be scanned for
            | application specific code. By default, we are scanning your app
            | folder, migrations folder and the seeders folder.
            |
            */
            'base_path' => [
                app_path(),
                database_path('migrations'),
                database_path('seeders'),
            ],

            /*
            |--------------------------------------------------------------------------
            | Environment Specific Analyzers
            |--------------------------------------------------------------------------
            |
            | There are some analyzers that are meant to be run for specific environments.
            | The options below specify whether we should skip environment specific
            | analyzers if the environment does not match.
            |
            */
            'skip_env_specific' => env('ENLIGHTN_SKIP_ENVIRONMENT_SPECIFIC', false),

            /*
            |--------------------------------------------------------------------------
            | Guest URL
            |--------------------------------------------------------------------------
            |
            | Specify any guest url or path (preferably your app's login url) here. This
            | would be used by Enlightn to inspect your application HTTP headers.
            | Example: '/login'.
            |
            */
            'guest_url' => null,

            /*
            |--------------------------------------------------------------------------
            | Exclusions From Reporting
            |--------------------------------------------------------------------------
            |
            | Specify the analyzer classes that you wish to exclude from reporting. This
            | means that if any of these analyzers fail, they will not be counted
            | towards the exit status of the Enlightn command. This is useful
            | if you wish to run the command in your CI/CD pipeline.
            | Example: [\Enlightn\Enlightn\Analyzers\Security\XSSAnalyzer::class].
            |
            */
            'dont_report' => [],

            /*
            |--------------------------------------------------------------------------
            | Ignoring Errors
            |--------------------------------------------------------------------------
            |
            | Use this config option to ignore specific errors. The key of this array
            | would be the analyzer class and the value would be an associative
            | array with path and details. Run php artisan enlightn:baseline
            | to auto-generate this. Patterns are supported in details.
            |
            */
            'ignore_errors' => [],

            /*
            |--------------------------------------------------------------------------
            | Analyzer Configurations
            |--------------------------------------------------------------------------
            |
            | The following configuration options pertain to individual analyzers.
            | These are recommended options but feel free to customize them based
            | on your application needs.
            |
            */
            'license_whitelist' => [
                'Apache-2.0', 'Apache2', 'BSD-2-Clause', 'BSD-3-Clause', 'LGPL-2.1-only', 'LGPL-2.1',
                'LGPL-2.1-or-later', 'LGPL-3.0', 'LGPL-3.0-only', 'LGPL-3.0-or-later', 'MIT', 'ISC',
                'CC0-1.0', 'Unlicense', 'WTFPL', 'proprietary'
            ],

            /*
            |--------------------------------------------------------------------------
            | Credentials
            |--------------------------------------------------------------------------
            |
            | The following credentials are used to share your Enlightn report with
            | the Enlightn Github Bot. This allows the bot to compile the report
            | and add review comments on your pull requests.
            |
            */
            'credentials' => [
                'username' => env('ENLIGHTN_USERNAME'),
                'api_token' => env('ENLIGHTN_API_TOKEN'),
            ],

            // Set this value to your Github repo for integrating with the Enlightn Github Bot
            // Format: "myorg/myrepo" like "laravel/framework".
            'github_repo' => env('ENLIGHTN_GITHUB_REPO'),

            // Set to true to restrict the max number of files displayed in the enlightn
            // command for each check. Set to false to display all files.
            'compact_lines' => true,

            // List your commercial packages (licensed by you) below, so that they are not
            // flagged by the License Analyzer.
            'commercial_packages' => [
                'enlightn/enlightnpro',
            ],

            'allowed_permissions' => [
                base_path() => '775',
                app_path() => '775',
                resource_path() => '775',
                storage_path() => '775',
                public_path() => '775',
                config_path() => '775',
                database_path() => '775',
                base_path('routes') => '775',
                app()->bootstrapPath() => '775',
                app()->bootstrapPath('cache') => '775',
                app()->bootstrapPath('app.php') => '664',
                base_path('artisan') => '775',
                public_path('index.php') => '664',
                public_path('server.php') => '664',
            ],

            'writable_directories' => [
                storage_path(),
                app()->bootstrapPath('cache'),
            ],

            /*
            |--------------------------------------------------------------------------
            | PHPStan Runtime configurations
            |--------------------------------------------------------------------------
            |
            | This setting allows us to pass through memory limits from artisan to phpstan.
            | using `php -d memory_limit=1G artisan enlightn`.
            */
            'phpstan' => [
                '--error-format' => 'json',
                '--no-progress' => true,
                '--memory-limit' => ini_get('memory_limit'),
            ],
        ];

        PHP_WRAP;
    }

    /**
     * Check if Enlightn is already installed
     */
    protected function isEnlightnInstalled(): bool
    {
        $composerJson = json_decode(File::get(base_path('composer.json')), true);

        return isset($composerJson['require-dev']['enlightn/enlightn']);
    }

    /**
     * Install Enlightn via Composer
     */
    protected function installEnlightn(Command $command): bool
    {
        $result = Process::path(base_path())
            ->timeout(300)
            ->run('composer require --dev enlightn/enlightn --with-all-dependencies', function ($type, $buffer) use ($command): void {
                $command->getOutput()->write($buffer);
            });

        return $result->successful();
    }

    /**
     * Ensure config/enlightn.php configuration exists and is properly configured
     */
    protected function ensureEnlightnConfiguration(Command $command): void
    {
        $configPath = base_path('config/enlightn.php');

        if (! File::exists($configPath)) {
            // Ensure config directory exists
            if (! File::exists(base_path('config'))) {
                File::makeDirectory(base_path('config'), 0755, true);
            }

            // No config/enlightn.php exists, create one with our defaults
            File::put($configPath, $this->getDefaultConfig());
            $command->info('✓ Created config/enlightn.php with default configuration');

            return;
        }

        // config/enlightn.php exists, preserve existing configuration
        $command->info('✓ config/enlightn.php configuration already exists (preserving existing configuration)');
    }
}
