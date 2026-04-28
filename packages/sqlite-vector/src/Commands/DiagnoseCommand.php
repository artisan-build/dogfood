<?php

declare(strict_types=1);

namespace ArtisanBuild\SqliteVector\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use PDO;

class DiagnoseCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'sqlite-vec:diagnose
                            {--cleanup : Clean up test data after diagnosis}';

    /**
     * The console command description.
     */
    protected $description = 'Diagnose sqlite-vec extension installation and configuration';

    protected bool $extensionLoaded = false;

    protected string $testTableName = '__sqlite_vec_diagnostic_test__';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Running sqlite-vec diagnostics...');
        $this->newLine();

        $allChecksPassed = true;

        // Check 1: Configuration
        $this->info('Checking configuration...');
        $config = $this->checkConfiguration();

        foreach ($config as $key => $value) {
            $display = is_bool($value) ? ($value ? 'true' : 'false') : $value;
            $this->line("  {$key}: {$display}");
        }

        $this->newLine();

        // Check 2: Connection
        $this->info('Checking database connection...');
        $connection = $this->checkConnection();

        if ($connection['success']) {
            $this->line("  ✓ Connection '{$connection['connection']}' is accessible");
            $this->line("  ✓ Driver: {$connection['driver']}");
        } else {
            $this->error("  ✗ Connection failed: {$connection['error']}");
            $allChecksPassed = false;
        }

        $this->newLine();

        // Check 3: Extension file
        $this->info('Checking extension file...');

        if ($this->checkExtensionFile()) {
            $this->line('  ✓ Extension file exists at: '.config('sqlite-vector.extension_path'));
        } else {
            $this->warn('  ⚠ Extension file not found at: '.config('sqlite-vector.extension_path'));
            $this->line("  Run 'php artisan sqlite-vec:install' to install the extension");
            $allChecksPassed = false;
        }

        $this->newLine();

        // Check 4: Try to load extension
        $this->info('Testing extension loading...');
        $loadTest = $this->testExtensionLoad();

        if ($loadTest['success']) {
            $this->line('  ✓ Extension loaded successfully');
            $this->extensionLoaded = true;
        } else {
            $this->error('  ✗ Failed to load extension: '.$loadTest['error']);
            $allChecksPassed = false;
        }

        $this->newLine();

        // Check 5: Test basic vector operations (only if extension loaded)
        if ($this->extensionLoaded) {
            $this->info('Testing vector operations...');
            $vectorTest = $this->testVectorOperations();

            if ($vectorTest['success']) {
                $this->line('  ✓ Vector table creation successful');
                $this->line('  ✓ Vector insertion successful');
                $this->line('  ✓ Vector distance calculation successful');
            } else {
                $this->error('  ✗ Vector operations failed: '.$vectorTest['error']);
                $allChecksPassed = false;
            }

            $this->newLine();
        }

        // Cleanup
        if ($this->option('cleanup') || $this->extensionLoaded) {
            $this->info('Cleaning up test data...');
            $this->cleanup();
            $this->line('  ✓ Cleanup complete');
            $this->newLine();
        }

        // Summary
        if ($allChecksPassed) {
            $this->info('✓ All diagnostics passed! sqlite-vec is properly configured.');

            return self::SUCCESS;
        } else {
            $this->warn('⚠ Some diagnostic checks failed. Review the output above for details.');

            return self::FAILURE;
        }
    }

    /**
     * Check configuration values.
     */
    public function checkConfiguration(): array
    {
        return [
            'connection' => config('sqlite-vector.connection'),
            'extension_path' => config('sqlite-vector.extension_path'),
            'default_dimensions' => config('sqlite-vector.default_dimensions'),
            'table_name' => config('sqlite-vector.table_name'),
            'metadata_table_name' => config('sqlite-vector.metadata_table_name'),
            'distance_metric' => config('sqlite-vector.distance_metric'),
            'auto_load_extension' => config('sqlite-vector.auto_load_extension'),
        ];
    }

    /**
     * Check database connection.
     */
    public function checkConnection(): array
    {
        try {
            $connection = config('sqlite-vector.connection');
            $pdo = DB::connection($connection)->getPdo();

            return [
                'success' => true,
                'connection' => $connection,
                'driver' => $pdo->getAttribute(PDO::ATTR_DRIVER_NAME),
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Check if extension file exists.
     */
    public function checkExtensionFile(): bool
    {
        return File::exists(config('sqlite-vector.extension_path'));
    }

    /**
     * Test loading the extension.
     */
    protected function testExtensionLoad(): array
    {
        if (! $this->checkExtensionFile()) {
            return [
                'success' => false,
                'error' => 'Extension file not found',
            ];
        }

        try {
            $connection = config('sqlite-vector.connection');
            $extensionPath = config('sqlite-vector.extension_path');

            DB::connection($connection)->getPdo()->exec("SELECT load_extension('{$extensionPath}')");

            return ['success' => true];
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Test basic vector operations.
     */
    protected function testVectorOperations(): array
    {
        try {
            $connection = config('sqlite-vector.connection');

            // Create test virtual table
            DB::connection($connection)->statement(
                "CREATE VIRTUAL TABLE {$this->testTableName} USING vec0(embedding float[3])"
            );

            // Insert test vector
            DB::connection($connection)->statement(
                "INSERT INTO {$this->testTableName} (embedding) VALUES ('[1.0, 2.0, 3.0]')"
            );

            // Test distance calculation
            $result = DB::connection($connection)->selectOne(
                "SELECT vec_distance_l2(embedding, '[1.0, 2.0, 3.0]') as distance
                FROM {$this->testTableName}
                LIMIT 1"
            );

            if ($result && $result->distance === 0.0) {
                return ['success' => true];
            }

            return [
                'success' => false,
                'error' => 'Distance calculation returned unexpected result',
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Clean up test data.
     */
    protected function cleanup(): void
    {
        try {
            $connection = config('sqlite-vector.connection');
            DB::connection($connection)->statement("DROP TABLE IF EXISTS {$this->testTableName}");
        } catch (Exception) {
            // Silently fail cleanup
        }
    }
}
