<?php

declare(strict_types=1);

namespace ArtisanBuild\AgentOsInstaller\Actions;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Process;

/**
 * Ensure Rector is installed and configured
 *
 * Minimum Requirements:
 * - PHP 8.2+
 * - Laravel 11.0+
 * - Composer 2.0+
 */
class EnsureRectorIsInstalled
{
    /**
     * Default Rector configuration template
     */
    protected string $defaultConfig = <<<'PHP'
<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use RectorLaravel\Set\LaravelLevelSetList;
use RectorLaravel\Set\LaravelSetList;

return RectorConfig::configure()
    ->withPaths([
        __DIR__.'/app',
        __DIR__.'/bootstrap',
        __DIR__.'/config',
        __DIR__.'/public',
        __DIR__.'/resources',
        __DIR__.'/routes',
        __DIR__.'/tests',
        __DIR__.'/database',
    ])
    ->withPhpSets(php84: true)
    ->withSets([
        LaravelLevelSetList::UP_TO_LARAVEL_120,
        LaravelSetList::LARAVEL_CODE_QUALITY,
        LaravelSetList::LARAVEL_COLLECTION,
    ])
    ->withImportNames(true, false, true, true)
    ->withTypeCoverageLevel(1)
    ->withDeadCodeLevel(1)
    ->withCodeQualityLevel(1)
    ->withImportNames(
        importShortClasses: false,
        removeUnusedImports: true,
    );

PHP;

    /**
     * Execute the action to ensure Rector is installed
     */
    public function __invoke(Command $command): bool
    {
        if (! $this->isRectorInstalled()) {
            $command->info('Installing Rector...');

            if (! $this->installRector($command)) {
                $command->error('Failed to install Rector');

                return false;
            }

            $command->info('✓ Rector installed successfully');
        } else {
            $command->info('✓ Rector is already installed');
        }

        // Ensure rector.php configuration
        $this->ensureRectorConfiguration($command);

        return true;
    }

    /**
     * Check if Rector is already installed
     */
    protected function isRectorInstalled(): bool
    {
        $composerJson = json_decode(File::get(base_path('composer.json')), true);

        return isset($composerJson['require-dev']['rector/rector']);
    }

    /**
     * Install Rector via Composer
     */
    protected function installRector(Command $command): bool
    {
        $result = Process::path(base_path())
            ->timeout(300)
            ->run('composer require --dev rector/rector driftingly/rector-laravel --with-all-dependencies', function ($type, $buffer) use ($command): void {
                $command->getOutput()->write($buffer);
            });

        return $result->successful();
    }

    /**
     * Ensure rector.php configuration exists
     */
    protected function ensureRectorConfiguration(Command $command): void
    {
        $rectorPhpPath = base_path('rector.php');

        if (! File::exists($rectorPhpPath)) {
            // No rector.php exists, create one with our defaults
            File::put($rectorPhpPath, $this->defaultConfig);
            $command->info('✓ Created rector.php with default configuration');
        } else {
            $command->info('✓ rector.php configuration already exists (preserving existing configuration)');
        }
    }
}
