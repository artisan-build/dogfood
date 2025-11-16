<?php

declare(strict_types=1);

namespace ArtisanBuild\SqliteVector\Providers;

use ArtisanBuild\SqliteVector\Commands\DiagnoseCommand;
use ArtisanBuild\SqliteVector\Commands\InstallSqliteVecCommand;
use ArtisanBuild\SqliteVector\EmbeddingManager;
use Illuminate\Database\Events\ConnectionEstablished;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use Override;

class SqliteVectorServiceProvider extends ServiceProvider
{
    #[Override]
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../../config/sqlite-vector.php', 'sqlite-vector');

        $this->app->singleton('sqlite-vector.manager', function ($app) {
            return new EmbeddingManager;
        });
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../../config/sqlite-vector.php' => config_path('sqlite-vector.php'),
        ], 'sqlite-vector-config');

        $this->publishes([
            __DIR__.'/../../database/migrations' => database_path('migrations'),
        ], 'sqlite-vector-migrations');

        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallSqliteVecCommand::class,
                DiagnoseCommand::class,
            ]);
        }

        // Load extension when configured connection is established
        if (config('sqlite-vector.auto_load_extension', true)) {
            Event::listen(ConnectionEstablished::class, function ($event) {
                $configuredConnection = config('sqlite-vector.connection', 'sqlite');

                if ($event->connectionName === $configuredConnection) {
                    $this->loadExtension($event->connection);
                }
            });
        }
    }

    /**
     * Load the sqlite-vec extension on the given connection.
     */
    protected function loadExtension($connection): void
    {
        $extensionPath = config('sqlite-vector.extension_path');

        if (! File::exists($extensionPath)) {
            Log::warning("sqlite-vec extension not found at {$extensionPath}. Run 'php artisan sqlite-vec:install' to install it.");

            return;
        }

        try {
            // Try SQL method first
            $connection->getPdo()->exec("SELECT load_extension('{$extensionPath}')");
        } catch (\Exception $e) {
            try {
                // Fall back to PDO method
                $connection->getPdo()->loadExtension($extensionPath);
            } catch (\Exception $e) {
                Log::warning("Failed to load sqlite-vec extension: {$e->getMessage()}");
            }
        }
    }
}
