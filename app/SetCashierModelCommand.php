<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Actions\Cook\IsPackageInstalled;
use App\Actions\Cook\ListApplicationModels;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Process;

use function Laravel\Prompts\select;

class SetCashierModelCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stripe:set-cashier-model';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set the model that Laravel Cashier will use for subscriptions';

    /**
     * The IsPackageInstalled action.
     */
    private readonly IsPackageInstalled $isPackageInstalled;

    /**
     * The ListApplicationModels action.
     */
    private readonly ListApplicationModels $listApplicationModels;

    /**
     * Create a new command instance.
     */
    public function __construct(IsPackageInstalled $isPackageInstalled, ListApplicationModels $listApplicationModels)
    {
        parent::__construct();
        $this->isPackageInstalled = $isPackageInstalled;
        $this->listApplicationModels = $listApplicationModels;
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        // If laravel/cashier is not installed, install it via composer
        if (! ($this->isPackageInstalled)('laravel/cashier')) {
            $this->info('Installing Laravel Cashier...');
            $this->executeCommand('composer require laravel/cashier');
        }

        // If the Laravel Cashier migrations have not been published, publish them
        if (! $this->areCashierMigrationsPublished()) {
            $this->info('Publishing Laravel Cashier migrations...');
            $this->executeCommand('php artisan vendor:publish --tag="cashier-migrations"');
        }

        // List models in the app and ask the user which model they want to use for subscriptions
        $models = ($this->listApplicationModels)();
        $defaultIndex = array_search(User::class, $models) ?: 0;
        $selectedModel = select(
            'Which model do you want to use for subscriptions?',
            $models,
            default: $defaultIndex
        );

        // If User is selected as the model, there is nothing more to do. Just return success code
        if ($selectedModel === User::class) {
            $this->info('User model is already the default model for Laravel Cashier. No changes needed.');

            return 0;
        }

        // Set the model up in the app service provider's boot method like this: Cashier::useCustomerModel(Model::class);
        $this->updateAppServiceProvider($selectedModel);

        // Update the model to use the Billable trait if it doesn't already
        $this->updateModelWithBillableTrait($selectedModel);

        // Update the Stripe migrations to use the correct table name for the selected model.
        $this->updateMigrations($selectedModel);

        $this->info("Laravel Cashier has been configured to use {$selectedModel} model.");

        return 0;
    }

    /**
     * Execute a shell command.
     */
    private function executeCommand(string $command): void
    {
        $result = Process::run($command);

        // Output stdout to console
        foreach (explode(PHP_EOL, $result->output()) as $line) {
            if (! empty(trim($line))) {
                $this->line($line);
            }
        }

        // Output stderr to console as errors
        foreach (explode(PHP_EOL, $result->errorOutput()) as $line) {
            if (! empty(trim($line))) {
                $this->error($line);
            }
        }
    }

    /**
     * Check if Cashier migrations have been published.
     */
    private function areCashierMigrationsPublished(): bool
    {
        $migrations = [
            'create_customer_columns.php',
            'create_subscriptions_table.php',
            'create_subscription_items_table.php',
        ];

        foreach ($migrations as $file) {
            $found = false;
            foreach (glob(database_path('migrations/*_'.$file)) as $migration) {
                $found = true;
                break;
            }
            if (! $found) {
                return false;
            }
        }

        return true;
    }

    /**
     * Update the AppServiceProvider to use the selected model for Cashier.
     */
    private function updateAppServiceProvider(string $modelClass): void
    {
        $providerPath = app_path('Providers/AppServiceProvider.php');
        $content = file_get_contents($providerPath);

        // Check if Cashier is already imported
        if (! str_contains($content, 'use Laravel\Cashier\Cashier;')) {
            $content = str_replace(
                'namespace App\Providers;',
                "namespace App\Providers;\n\nuse Laravel\Cashier\Cashier;",
                $content
            );
        }

        // Check if the boot method already has content
        if (str_contains($content, 'public function boot(): void
    {
        //
    }')) {
            $content = str_replace(
                'public function boot(): void
    {
        //
    }',
                "public function boot(): void
    {
        Cashier::useCustomerModel(\\{$modelClass}::class);
    }",
                $content
            );
        } else {
            // If the boot method already has content, add the Cashier line
            $content = preg_replace(
                '/(public function boot\(\): void\s*\{)/',
                "$1\n        Cashier::useCustomerModel(\\{$modelClass}::class);",
                $content
            );
        }

        file_put_contents($providerPath, $content);
        $this->info('AppServiceProvider updated.');
    }

    /**
     * Update the migrations to use the correct table name for the selected model.
     */
    private function updateMigrations(string $modelClass): void
    {
        // Get the table name for the selected model
        $modelInstance = new $modelClass;
        $tableName = $modelInstance->getTable();

        // Update the customer columns migration
        foreach (glob(database_path('migrations/*_create_customer_columns.php')) as $migration) {
            $content = file_get_contents($migration);

            // Update the table name
            // Update the down method table name
            $content = str_replace(["Schema::table('users'", "Schema::table('users', function (Blueprint \$table) {"], ["Schema::table('{$tableName}'", "Schema::table('{$tableName}', function (Blueprint \$table) {"], $content);

            file_put_contents($migration, $content);
        }

        // Update the subscriptions table migration
        foreach (glob(database_path('migrations/*_create_subscriptions_table.php')) as $migration) {
            $content = file_get_contents($migration);

            $foreignKey = $modelInstance->getForeignKey();

            // Check if the model uses UUID as primary key
            if ($modelInstance->getKeyType() === 'string' && ! $modelInstance->getIncrementing()) {
                $content = str_replace(
                    '$table->foreignId(\'user_id\');',
                    '$table->foreignUuid(\''.$foreignKey.'\');',
                    $content
                );
            } else {
                $content = str_replace(
                    'user_id',
                    $foreignKey,
                    $content
                );
            }

            // Update the index
            $content = str_replace(
                '$table->index([\'user_id\', \'stripe_status\']);',
                '$table->index([\''.$foreignKey.'\', \'stripe_status\']);',
                $content
            );

            file_put_contents($migration, $content);
        }

        $this->info('Migrations updated.');
    }

    /**
     * Update the model to use the Billable trait if it doesn't already.
     */
    private function updateModelWithBillableTrait(string $modelClass): void
    {
        $modelPath = app_path('Models/'.class_basename($modelClass).'.php');

        if (! file_exists($modelPath)) {
            $this->error("Model file not found: {$modelPath}");

            return;
        }

        $content = file_get_contents($modelPath);

        // Check if the model already uses the Billable trait
        if (str_contains($content, 'use Laravel\Cashier\Billable;') ||
            str_contains($content, 'use Billable;')) {
            $this->info('Model already uses the Billable trait.');

            return;
        }

        $pattern = '/namespace\s+([^;]+);/';
        preg_match($pattern, $content, $matches);

        if (isset($matches[0])) {
            $content = str_replace(
                $matches[0],
                $matches[0]."\n\nuse Laravel\Cashier\Billable;",
                $content
            );
        }

        // Add the use Billable; statement to the class
        $pattern = '/class\s+'.class_basename($modelClass).'\s+extends\s+[^\s{]+\s*{/';
        preg_match($pattern, $content, $matches);

        if (isset($matches[0])) {
            // Find the first use statement in the class
            $usePattern = '/\s+use\s+[^;]+;/';
            preg_match($usePattern, $content, $useMatches, PREG_OFFSET_CAPTURE, strpos($content, $matches[0]));

            if (isset($useMatches[0])) {
                // Add after the last use statement
                $lastUsePos = strrpos($content, 'use', strpos($content, $matches[0]));
                $semicolonPos = strpos($content, ';', $lastUsePos);

                $content = substr_replace(
                    $content,
                    ";\n    use Billable;",
                    $semicolonPos,
                    1
                );
            } else {
                // No existing use statements, add after the class declaration
                $content = str_replace(
                    $matches[0],
                    $matches[0]."\n    use Billable;",
                    $content
                );
            }
        }

        file_put_contents($modelPath, $content);
        $this->info('Model updated to use the Billable trait.');
    }
}
