<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Stripe\Exception\ApiErrorException;
use Stripe\Product;
use Stripe\StripeClient;

use function Laravel\Prompts\confirm;
use function Laravel\Prompts\error;
use function Laravel\Prompts\info;
use function Laravel\Prompts\note;
use function Laravel\Prompts\spin;
use function Laravel\Prompts\table;
use function Laravel\Prompts\text;
use function Laravel\Prompts\warning;

class SetUpStripePricesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stripe:setup-prices
                            {--force : Skip confirmation in production environment}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set up Stripe products and prices from config/plans.php';

    protected StripeClient $stripe;

    /**
     * The name of the state file to track plan changes.
     */
    protected string $stateFileName = 'stripe-plans-state.json';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $isProduction = app()->environment('production');
        $force = $this->option('force');

        // Check if plans have changed
        $currentPlans = config('plans');

        if (Storage::exists($this->stateFileName)) {
            $savedState = json_decode((string) Storage::get($this->stateFileName), true);
            $currentState = $this->generateStateHash($currentPlans);

            if ($savedState['hash'] === $currentState) {
                info('No changes detected in plans configuration. Skipping update.');

                return 0;
            }

            info('Changes detected in plans configuration. Updating Stripe...');
        } else {
            info('First time setup detected. Creating Stripe products and prices...');
        }

        if ($isProduction && ! $force) {
            warning('You are running in PRODUCTION environment!');
            if (! confirm('Are you sure you want to set up Stripe prices in production?', false)) {
                info('Operation cancelled.');

                return 0;
            }
        }

        if ($isProduction) {
            warning('Running in PRODUCTION environment');
        } else {
            info('Running in '.app()->environment().' environment');
        }

        $stripeKey = $this->ensureStripeCredentials();

        $this->stripe = new StripeClient($stripeKey);

        try {
            $product = spin(
                fn () => $this->setupProduct(),
                'Setting up product...'
            );

            spin(
                fn () => $this->setupPrices($product),
                'Creating prices...'
            );

            // Save the current state
            $this->saveState($currentPlans);

            info('✓ Stripe setup completed successfully!');

            return 0;
        } catch (ApiErrorException $e) {
            error('Stripe API Error: '.$e->getMessage());

            return 1;
        } catch (\Exception $e) {
            error('Error: '.$e->getMessage());

            return 1;
        }
    }

    protected function setupProduct(): Product
    {
        $productConfig = config('plans.product');

        $existingProducts = $this->stripe->products->search([
            'query' => 'metadata["app"]:"'.$productConfig['metadata']['app'].'"',
        ]);

        if ($existingProducts->count() > 0) {
            // Always update existing product to match config
            $product = $this->stripe->products->update(
                $existingProducts->data[0]->id,
                [
                    'name' => $productConfig['name'],
                    'description' => $productConfig['description'],
                    'metadata' => $productConfig['metadata'],
                ]
            );

            return $product;
        }

        // Create new product if none exists
        $product = $this->stripe->products->create([
            'name' => $productConfig['name'],
            'description' => $productConfig['description'],
            'metadata' => $productConfig['metadata'],
        ]);

        return $product;
    }

    protected function setupPrices(Product $product): void
    {
        $plans = config('plans.plans');
        /** @var array<string, string> $envUpdates */
        $envUpdates = [];
        /** @var array<int, array<int, string>> $createdPrices */
        $createdPrices = [];

        foreach ($plans as $planKey => $planConfig) {
            // Check if price already exists by searching for metadata
            $metadataQuery = 'metadata["plan_type"]:"'.$planConfig['metadata']['plan_type'].'" AND product:"'.$product->id.'"';
            $existingPrices = $this->stripe->prices->search([
                'query' => $metadataQuery,
            ]);

            if ($existingPrices->count() > 0) {
                // Price exists, use it
                $price = $existingPrices->data[0];
            } else {
                // Create new price
                $priceData = [
                    'product' => $product->id,
                    'currency' => $planConfig['currency'],
                    'unit_amount' => $planConfig['price'],
                    'nickname' => $planConfig['nickname'],
                    'metadata' => $planConfig['metadata'],
                ];

                if ($planConfig['interval']) {
                    $priceData['recurring'] = [
                        'interval' => $planConfig['interval'],
                        'interval_count' => $planConfig['interval_count'] ?? 1,
                    ];

                    if ($planConfig['trial_period_days']) {
                        $priceData['recurring']['trial_period_days'] = $planConfig['trial_period_days'];
                    }
                }

                $price = $this->stripe->prices->create($priceData);
            }

            $envKey = 'STRIPE_PRICE_'.strtoupper((string) $planKey);
            $envUpdates[$envKey] = $price->id;

            $createdPrices[] = [
                (string) $planConfig['nickname'],
                (string) $price->id,
                '$'.number_format($planConfig['price'] / 100, 2),
                (string) ($planConfig['interval'] ?? 'one-time'),
            ];
        }

        if (! empty($envUpdates)) {
            table(['Plan', 'Price ID', 'Amount', 'Interval'], $createdPrices);

            if (confirm('Would you like to write these values to your .env file?')) {
                $this->updateEnvFile($envUpdates);
                info('✓ Environment variables have been written to .env file');
            } else {
                warning('Environment variables were not written. You can manually add them later.');
            }
        }
    }

    /**
     * @param  array<string, string>  $envUpdates
     */
    protected function updateEnvFile(array $envUpdates): void
    {
        $envPath = base_path('.env');

        if (! file_exists($envPath)) {
            error('.env file not found!');

            return;
        }

        $envContent = file_get_contents($envPath);

        foreach ($envUpdates as $key => $value) {
            $pattern = "/^{$key}=.*/m";

            if (preg_match($pattern, $envContent)) {
                // Update existing value
                $envContent = preg_replace($pattern, "{$key}={$value}", $envContent);
            } else {
                // Add new value
                $envContent .= "\n{$key}={$value}";
            }
        }

        file_put_contents($envPath, $envContent);
    }

    /**
     * Generate a hash of the current plans configuration.
     *
     * @param  array<string, mixed>  $plans
     */
    protected function generateStateHash(array $plans): string
    {
        // Remove dynamic values like stripe_price_id from the comparison
        $plansForComparison = $plans;
        if (isset($plansForComparison['plans'])) {
            foreach ($plansForComparison['plans'] as &$plan) {
                unset($plan['stripe_price_id']);
            }
        }

        return hash('sha256', json_encode($plansForComparison));
    }

    /**
     * @param  array<string, mixed>  $plans
     */
    protected function saveState(array $plans): void
    {
        $state = [
            'hash' => $this->generateStateHash($plans),
            'updated_at' => now()->toIso8601String(),
            'plans' => $plans,
        ];

        Storage::put($this->stateFileName, json_encode($state, JSON_PRETTY_PRINT));
    }

    protected function ensureStripeCredentials(): string
    {
        $stripeKey = config('services.stripe.secret');
        /** @var array<string, string> $envUpdates */
        $envUpdates = [];

        if (! $stripeKey) {
            note(
                "Stripe API credentials not found!\n\n".
                "To get your Stripe API keys:\n".
                "1. Go to https://dashboard.stripe.com/apikeys\n".
                "2. Sign in to your Stripe account\n".
                "3. Copy your Secret key (starts with 'sk_')\n".
                "   - Use 'Test mode' keys for development\n".
                "   - Use 'Live mode' keys for production"
            );

            $stripeKey = text(
                label: 'Enter your Stripe Secret key',
                placeholder: 'sk_test_... or sk_live_...',
                required: true,
                validate: function ($value) {
                    if (! str_starts_with($value, 'sk_test_') && ! str_starts_with($value, 'sk_live_')) {
                        return 'Stripe secret keys must start with sk_test_ or sk_live_';
                    }
                }
            );

            $envUpdates['STRIPE_SECRET'] = $stripeKey;
        }

        // Check for publishable key (often needed for frontend)
        $publishableKey = config('services.stripe.key');
        if (! $publishableKey) {
            note(
                "Stripe Publishable key not found!\n\n".
                "While you're in the Stripe dashboard:\n".
                "Copy your Publishable key (starts with 'pk_')\n".
                'This is needed for frontend payment forms'
            );

            $publishableKey = text(
                label: 'Enter your Stripe Publishable key (optional)',
                placeholder: 'pk_test_... or pk_live_...',
                required: false,
                validate: function ($value) {
                    if ($value && ! str_starts_with($value, 'pk_test_') && ! str_starts_with($value, 'pk_live_')) {
                        return 'Stripe publishable keys must start with pk_test_ or pk_live_';
                    }
                }
            );

            if ($publishableKey) {
                $envUpdates['STRIPE_KEY'] = $publishableKey;
            }
        }

        // Check for webhook secret (if using webhooks)
        $webhookSecret = config('services.stripe.webhook.secret');
        if (! $webhookSecret && confirm('Will you be using Stripe webhooks?', false)) {
            note(
                "To set up Stripe webhooks:\n\n".
                "1. Go to https://dashboard.stripe.com/webhooks\n".
                "2. Click 'Add endpoint'\n".
                '3. Enter your endpoint URL: '.url('/stripe/webhook')."\n".
                "4. Select the events you want to listen for\n".
                "5. After creating, copy the 'Signing secret' (starts with 'whsec_')"
            );

            $webhookSecret = text(
                label: 'Enter your Stripe Webhook signing secret',
                placeholder: 'whsec_...',
                required: false,
                validate: function ($value) {
                    if ($value && ! str_starts_with($value, 'whsec_')) {
                        return 'Stripe webhook secrets must start with whsec_';
                    }
                }
            );

            if ($webhookSecret) {
                $envUpdates['STRIPE_WEBHOOK_SECRET'] = $webhookSecret;
            }
        }

        if (! empty($envUpdates)) {
            if (confirm('Would you like to save these credentials to your .env file?')) {
                $this->updateEnvFile($envUpdates);
                info('✓ Stripe credentials have been saved to .env file');

                // Reload config
                app()->make('config')->set('services.stripe.secret', $stripeKey);
            }
        }

        return $stripeKey;
    }
}
