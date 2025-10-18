<?php

declare(strict_types=1);

namespace ArtisanBuild\AgentOsInstaller\Actions;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Process;

/**
 * Ensure PHP_CodeSniffer with Slevomat coding standard is installed and configured
 *
 * Minimum Requirements:
 * - PHP 8.2+
 * - Laravel 11.0+
 * - Composer 2.0+
 */
class EnsurePhpCodeSnifferIsInstalled
{
    /**
     * Default PHP_CodeSniffer configuration
     */
    protected string $defaultConfig = <<<'XML_WRAP'
    <?xml version="1.0"?>
    <ruleset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
             name="Code Quality Advisor"
             xsi:noNamespaceSchemaLocation="vendor/squizlabs/php_codesniffer/phpcs.xsd">

        <description>Code quality analysis for refactoring opportunities - not for enforcing style (Pint handles that)</description>

        <!-- Show progress and use colors -->
        <arg value="p"/>
        <arg name="colors"/>

        <!-- Use caching for better performance -->
        <arg name="cache" value=".phpcs.cache"/>

        <!-- Parallel processing for speed -->
        <arg name="parallel" value="8"/>

        <!-- What to scan -->
        <file>app</file>
        <file>config</file>
        <file>routes</file>

        <!-- Excluded paths -->
        <exclude-pattern>*/vendor/*</exclude-pattern>
        <exclude-pattern>*/bootstrap/cache/*</exclude-pattern>
        <exclude-pattern>*/storage/*</exclude-pattern>
        <exclude-pattern>*/node_modules/*</exclude-pattern>
        <exclude-pattern>*/_ide_helper*.php</exclude-pattern>
        <exclude-pattern>*.blade.php</exclude-pattern>
        <exclude-pattern>*/tests/*</exclude-pattern>
        <exclude-pattern>*Test.php</exclude-pattern>
        <exclude-pattern>*/database/migrations/*</exclude-pattern>
        <exclude-pattern>*/database/seeders/*</exclude-pattern>
        <exclude-pattern>*/database/factories/*</exclude-pattern>

        <!-- ==============================================
             CODE COMPLEXITY - Refactoring Opportunities
             ============================================== -->

        <!-- Cognitive Complexity - surfaces complex methods that could be simplified -->
        <rule ref="SlevomatCodingStandard.Complexity.Cognitive">
            <properties>
                <!-- Reasonable threshold for Laravel controllers -->
                <property name="maxComplexity" value="15"/>
            </properties>
        </rule>

        <!-- ==============================================
             TYPE COVERAGE - Find Missing Type Hints
             ============================================== -->

        <!-- Parameter type hints - improves code safety -->
        <rule ref="SlevomatCodingStandard.TypeHints.ParameterTypeHint">
            <properties>
                <property name="enableObjectTypeHint" value="true"/>
                <property name="enableMixedTypeHint" value="true"/>
                <property name="enableUnionTypeHint" value="true"/>
                <property name="enableIntersectionTypeHint" value="true"/>
            </properties>
        </rule>

        <!-- Property type hints - excludes Laravel framework classes -->
        <rule ref="SlevomatCodingStandard.TypeHints.PropertyTypeHint">
            <properties>
                <property name="enableNativeTypeHint" value="true"/>
                <property name="enableMixedTypeHint" value="true"/>
                <property name="enableUnionTypeHint" value="true"/>
                <property name="enableIntersectionTypeHint" value="true"/>
            </properties>
            <!-- Exclude Commands: $signature, $description can't be typed (parent doesn't type them) -->
            <exclude-pattern>*/app/Console/Commands/*</exclude-pattern>
            <!-- Exclude Models: $fillable, $hidden, $casts, etc. can't be typed (parent doesn't type them) -->
            <exclude-pattern>*/app/Models/*</exclude-pattern>
        </rule>

        <!-- Return type hints -->
        <rule ref="SlevomatCodingStandard.TypeHints.ReturnTypeHint">
            <properties>
                <property name="enableObjectTypeHint" value="true"/>
                <property name="enableStaticTypeHint" value="true"/>
                <property name="enableMixedTypeHint" value="true"/>
                <property name="enableUnionTypeHint" value="true"/>
                <property name="enableIntersectionTypeHint" value="true"/>
                <property name="enableNeverTypeHint" value="true"/>
            </properties>
        </rule>

        <!-- ==============================================
             DEAD CODE DETECTION - Cleanup Opportunities
             ============================================== -->

        <!-- Unused variables -->
        <rule ref="SlevomatCodingStandard.Variables.UnusedVariable"/>

        <!-- Unused use statements -->
        <rule ref="SlevomatCodingStandard.Namespaces.UnusedUses">
            <properties>
                <property name="searchAnnotations" value="true"/>
            </properties>
        </rule>

        <!-- Useless variable assignments -->
        <rule ref="SlevomatCodingStandard.Variables.UselessVariable"/>

        <!-- ==============================================
             CODE SMELL DETECTION - Logic Improvements
             ============================================== -->

        <!-- Useless conditions that could be simplified -->
        <rule ref="SlevomatCodingStandard.ControlStructures.UselessIfConditionWithReturn"/>

        <!-- Useless ternary operators -->
        <rule ref="SlevomatCodingStandard.ControlStructures.UselessTernaryOperator"/>

        <!-- Prefer early exit over else (reduces nesting) -->
        <rule ref="SlevomatCodingStandard.ControlStructures.EarlyExit"/>

        <!-- Require null coalesce operator ?? where applicable -->
        <rule ref="SlevomatCodingStandard.ControlStructures.RequireNullCoalesceOperator"/>

        <!-- ==============================================
             MODERN PHP PATTERNS - Upgrade Opportunities
             ============================================== -->

        <!-- Constructor property promotion should be used where possible -->
        <rule ref="SlevomatCodingStandard.Classes.RequireConstructorPropertyPromotion"/>

        <!-- ==============================================
             EXCEPTIONS HANDLING
             ============================================== -->

        <!-- Dead catch - catches exception but doesn't use it -->
        <rule ref="SlevomatCodingStandard.Exceptions.DeadCatch"/>

        <!-- ==============================================
             LARAVEL-SPECIFIC ADJUSTMENTS
             ============================================== -->

        <!-- Allow mixed type hints where needed for Laravel's magic -->
        <rule ref="SlevomatCodingStandard.TypeHints.DisallowMixedTypeHint">
            <severity>0</severity>
        </rule>

        <!-- Allow array type hints for Laravel's flexibility -->
        <rule ref="SlevomatCodingStandard.TypeHints.DisallowArrayTypeHintSyntax">
            <severity>0</severity>
        </rule>

    </ruleset>
    XML_WRAP;

    /**
     * Execute the action to ensure PHP_CodeSniffer is installed
     */
    public function __invoke(Command $command): bool
    {
        if (! $this->isPhpCodeSnifferInstalled()) {
            $command->info('Installing PHP_CodeSniffer and Slevomat coding standard...');

            if (! $this->installPhpCodeSniffer($command)) {
                $command->error('Failed to install PHP_CodeSniffer');

                return false;
            }

            $command->info('✓ PHP_CodeSniffer installed successfully');
        } else {
            $command->info('✓ PHP_CodeSniffer is already installed');
        }

        // Ensure phpcs.xml configuration
        $this->ensurePhpCodeSnifferConfiguration($command);

        // Ensure .gitignore includes .phpcs.cache
        $this->ensureGitignoreConfiguration($command);

        // Ensure composer.json allows the phpcodesniffer-composer-installer plugin
        $this->ensureComposerAllowPlugins($command);

        return true;
    }

    /**
     * Check if PHP_CodeSniffer is already installed
     */
    protected function isPhpCodeSnifferInstalled(): bool
    {
        $composerJson = json_decode(File::get(base_path('composer.json')), true);

        return isset($composerJson['require-dev']['squizlabs/php_codesniffer'])
            || isset($composerJson['require-dev']['slevomat/coding-standard']);
    }

    /**
     * Install PHP_CodeSniffer via Composer
     */
    protected function installPhpCodeSniffer(Command $command): bool
    {
        // First, allow the phpcodesniffer-composer-installer plugin
        $allowPluginResult = Process::path(base_path())
            ->timeout(30)
            ->run('composer config --no-plugins allow-plugins.dealerdirect/phpcodesniffer-composer-installer true', function ($type, $buffer) use ($command): void {
                $command->getOutput()->write($buffer);
            });

        if (! $allowPluginResult->successful()) {
            $command->error('Failed to allow phpcodesniffer-composer-installer plugin');

            return false;
        }

        $packages = [
            'slevomat/coding-standard',
            'dealerdirect/phpcodesniffer-composer-installer',
        ];

        $result = Process::path(base_path())
            ->timeout(300)
            ->run('composer require --dev '.implode(' ', $packages).' --with-all-dependencies', function ($type, $buffer) use ($command): void {
                $command->getOutput()->write($buffer);
            });

        return $result->successful();
    }

    /**
     * Ensure phpcs.xml configuration exists and is properly configured
     */
    protected function ensurePhpCodeSnifferConfiguration(Command $command): void
    {
        $phpcsXmlPath = base_path('phpcs.xml');

        if (! File::exists($phpcsXmlPath)) {
            // No phpcs.xml exists, create one with our defaults
            File::put($phpcsXmlPath, $this->defaultConfig);
            $command->info('✓ Created phpcs.xml with default configuration');

            return;
        }

        // phpcs.xml exists, preserve existing configuration
        $command->info('✓ phpcs.xml configuration already exists (preserving existing configuration)');
    }

    /**
     * Ensure .gitignore includes .phpcs.cache
     */
    protected function ensureGitignoreConfiguration(Command $command): void
    {
        $gitignorePath = base_path('.gitignore');

        if (! File::exists($gitignorePath)) {
            return;
        }

        $gitignoreContent = File::get($gitignorePath);

        if (str_contains($gitignoreContent, '.phpcs.cache')) {
            $command->info('✓ .gitignore already includes .phpcs.cache');

            return;
        }

        // Add .phpcs.cache to .gitignore
        File::append($gitignorePath, "\n.phpcs.cache\n");
        $command->info('✓ Added .phpcs.cache to .gitignore');
    }

    /**
     * Ensure composer.json allows the phpcodesniffer-composer-installer plugin
     */
    protected function ensureComposerAllowPlugins(Command $command): void
    {
        $composerJsonPath = base_path('composer.json');
        $composerJson = json_decode(File::get($composerJsonPath), true);

        $plugin = 'dealerdirect/phpcodesniffer-composer-installer';

        // Check if allow-plugins already includes the plugin
        if (isset($composerJson['config']['allow-plugins'][$plugin])) {
            $command->info('✓ composer.json already allows '.$plugin);

            return;
        }

        // Add the plugin to allow-plugins
        if (! isset($composerJson['config'])) {
            $composerJson['config'] = [];
        }
        if (! isset($composerJson['config']['allow-plugins'])) {
            $composerJson['config']['allow-plugins'] = [];
        }

        $composerJson['config']['allow-plugins'][$plugin] = true;

        // Write back to composer.json
        File::put($composerJsonPath, json_encode($composerJson, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)."\n");

        $command->info('✓ Added '.$plugin.' to composer.json allow-plugins');
    }
}
