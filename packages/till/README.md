# Till

A companion to Laravel Cashier that provides Verbs-enabled subscription management with powerful abilities, ledgers, and flexible payment processor integration.

> [!WARNING]
> This package is currently under active development, and we have not yet released a major version. Once a 0.* version
> has been tagged, we strongly recommend locking your application to a specific working version because we might make
> breaking changes even in patch releases until we've tagged 1.0.

## Overview

Till is a subscription management system built on top of Verbs (event sourcing) that provides:

- **Plan-based subscriptions** with individual and team modes
- **Ability system** for granular feature control
- **Ledger system** for usage-based billing (credits, quotas, etc.)
- **Event-sourced state** for reliable subscription tracking
- **Payment processor abstraction** (Stripe, Demo mode, extensible)
- **Built-in pricing UI components** with Livewire and Flux UI
- **Command-line scaffolding** for plans and abilities

Unlike traditional subscription packages that simply track billing, Till provides a complete feature management system where you define what users can do based on their subscription plan.

## Requirements

- PHP 8.3+
- Laravel 11.36+ or Laravel 12+
- Verbs package (event sourcing)
- Flux Themes package
- Sushi (for plan data modeling)

## Installation

Install the package via Composer:

```bash
composer require artisan-build/till
```

### Running the Installer

```bash
php artisan till:install
```

The installer will:

1. Publish the configuration file to `config/till.php`
2. Ask where you want to store your Plans (default: `app/Plans`)
3. Detect team mode (based on presence of `Team` model)
4. Configure user and team models
5. Create the Plans directory and Abilities subdirectory
6. Generate an `UnsubscribedPlan` as your default free tier

**Options:**

```bash
# Specify plans directory location
php artisan till:install Plans

# Undo installation and remove all published files
php artisan till:install --undo

# Force reinstall even if already installed
php artisan till:install --force
```

## Configuration

The configuration file is published to `config/till.php`:

```php
return [
    // Payment processor (Demo, Stripe)
    'payment_processor' => PaymentProcessors::Demo,

    // Enable team-based subscriptions vs individual subscriptions
    'team_mode' => true,

    // Model configuration
    'team_model' => \App\Models\Team::class,
    'user_model' => \App\Models\User::class,

    // Use live or test prices (null = auto-detect based on environment)
    'live_or_test' => env('TILL_LIVE_OR_TEST'),

    // Route URIs
    'subscribe_uri' => env('TILL_SUBSCRIBE_URI', 'subscribe'),
    'pricing_uri' => env('TILL_PRICING_URI', 'pricing'),
    'plans_uri' => env('TILL_PLANS_URI', 'plans'),

    // Default pricing display (week, month, year, life)
    'default_display' => env('TILL_DEFAULT_DISPLAY', 'year'),

    // Always show lifetime pricing option
    'always_show_lifetime' => true,

    // Path to your subscription plans
    'plan_path' => app_path('Plans'),

    // Livewire component for pricing display
    'pricing_section_template' => 'till::livewire.pricing_section',

    // Custom icons for active/inactive features
    'active_feature_icon' => null,
    'inactive_feature_icon' => null,

    // Ledger update frequencies
    'update_ledgers' => [
        'hourly' => true,
        'daily' => true,
        'weekly' => true,
        'monthly' => true,
        'yearly' => true,
    ],
];
```

## Core Concepts

### Plans

Plans define subscription tiers with pricing, features, and abilities. Each plan is a PHP class that extends `BasePlan`.

**Key Plan Properties:**

- `$prices` - Array of prices for different terms (week, month, year, life)
- `$features` - Array of features displayed on pricing pages
- `$can` - Array of abilities subscribers can perform
- `$wallet` - Array of ledger balances (for usage-based features)
- `$heading` - Display name for the plan
- `$subheading` - Tagline or description
- `$badge` - Badge configuration for plan display

### Abilities

Abilities are invokable classes that determine what a subscriber can do. Each ability returns a boolean indicating permission.

**Example Ability:**

```php
namespace App\Plans\Abilities;

class SendEmails
{
    public function __invoke(?int $limit = null)
    {
        // Unlimited if no limit specified
        if ($limit === null) {
            return true;
        }

        $sent = auth()->user()->emails()->thisMonth()->count();

        return $sent < $limit;
    }
}
```

### Ledgers

Ledgers track usage-based resources (credits, API calls, storage, etc.). They support:

- **Deposits** - Add credits to a ledger
- **Withdrawals** - Remove credits from a ledger
- **Spending** - Track usage with automatic balance checking
- **Period-based resets** - Hourly, daily, weekly, monthly, yearly

### Subscriber State

The `SubscriberState` is a Verbs state that tracks:

- Current plan
- Renewal and expiration dates
- Wallet balances for ledgers
- Transaction history

## Creating Plans

### Using the Command

```bash
php artisan till:create-plan
```

The command will prompt you for:

1. **Class name** - e.g., `StarterPlan`, `ProPlan`
2. **Heading** - Display name like "Pro"
3. **Subheading** - Tagline like "For growing teams"
4. **Prices** - Cost for week, month, year, and lifetime

This generates a plan class in your configured plans directory.

### Manual Plan Creation

```php
namespace App\Plans;

use ArtisanBuild\Till\Attributes\TeamPlan;
use ArtisanBuild\Till\Enums\PlanTerms;
use ArtisanBuild\Till\SubscriptionPlans\BasePlan;

#[TeamPlan]
class ProPlan extends BasePlan
{
    public array $prices = [
        PlanTerms::Month->value => 49.00,
        PlanTerms::Year->value => 490.00,
        PlanTerms::Life->value => 1490.00,
    ];

    public string $heading = 'Pro';
    public string $subheading = 'For growing teams';

    public array $features = [
        ['text' => 'Unlimited team members', 'icon' => 'users'],
        ['text' => '10,000 API calls per month', 'icon' => 'cloud'],
        ['text' => 'Priority support', 'icon' => 'chat'],
    ];

    public array $can = [
        ['AddUnlimitedSeats', []],
        ['SendEmails', ['limit' => 10000]],
        ['AccessAdvancedReports', []],
    ];
}
```

## Using Abilities

### Adding the Tillable Trait

Add the `Tillable` trait to your User model:

```php
use ArtisanBuild\Till\Traits\Tillable;

class User extends Authenticatable
{
    use Tillable;
}
```

This provides:

- `subscription()` - Get the subscriber state
- `abilities()` - Get array of all abilities
- `ableTo($ability)` - Check a specific ability

### Checking Abilities

```php
// In controllers
if (auth()->user()->ableTo('send-emails')) {
    // Send email
}

// In Blade templates
@if(auth()->user()->ableTo('access-advanced-reports'))
    <a href="{{ route('reports.advanced') }}">Advanced Reports</a>
@endif

// Using middleware (planned feature)
Route::middleware(['till:send-emails'])->group(function () {
    // Protected routes
});
```

## Working with Ledgers

### Defining Ledgers

Create an enum for your ledgers:

```php
namespace App\Plans;

enum Ledgers: string
{
    case ApiCalls = 'api-calls';
    case Storage = 'storage';
    case Emails = 'emails';
}
```

### Using Ledgers in Plans

```php
use ArtisanBuild\Till\Enums\LedgerPeriods;

class ProPlan extends BasePlan
{
    public array $wallet = [
        Ledgers::ApiCalls->value => [
            'balance' => 10000,
            'period' => LedgerPeriods::Monthly,
        ],
        Ledgers::Storage->value => [
            'balance' => 100, // GB
            'period' => null, // Never resets
        ],
    ];
}
```

### Spending from Ledgers

```php
use ArtisanBuild\Till\Events\LedgerDebited;

// Spend 1 credit from a ledger
LedgerDebited::fire(
    subscriber_id: auth()->user()->subscriberId(),
    ledger: Ledgers::ApiCalls,
    amount: 1
);

// Check balance before spending
$state = auth()->user()->subscription();
$balance = $state->wallet[Ledgers::ApiCalls->value] ?? 0;

if ($balance >= 10) {
    // Proceed with operation
}
```

### Depositing to Ledgers

```php
$state = auth()->user()->subscription();
$state->deposit(Ledgers::ApiCalls->value, 5000);
```

### Cost Attributes

Mark actions with costs using the `#[Costs]` attribute:

```php
use ArtisanBuild\Till\Attributes\Costs;

#[Costs(ledger: Ledgers::ApiCalls, amount: 1)]
class ProcessApiRequest
{
    public function __invoke($request)
    {
        // Processing logic
    }
}
```

## Subscription Events

Till uses event sourcing through Verbs. Key events:

### SubscriptionStarted

```php
use ArtisanBuild\Till\Events\SubscriptionStarted;

SubscriptionStarted::fire(
    subscriber_id: $user->id,
    plan_id: 'pro-plan',
    renews_at: now()->addMonth(),
    expires_at: null
);
```

### SubscriptionCacheUpdated

Automatically fired when abilities need recalculation:

```php
use ArtisanBuild\Till\Events\SubscriptionCacheUpdated;

// Manually trigger cache refresh
SubscriptionCacheUpdated::commit(
    subscriber_id: $user->id
);
```

### NewSubscriberAddedToDefaultPlan

Fired when a new user is created:

```php
use ArtisanBuild\Till\Events\NewSubscriberAddedToDefaultPlan;

NewSubscriberAddedToDefaultPlan::fire(
    subscriber_id: $user->id
);
```

## Plan Attributes

### Plan Type Attributes

```php
use ArtisanBuild\Till\Attributes\IndividualPlan;
use ArtisanBuild\Till\Attributes\TeamPlan;
use ArtisanBuild\Till\Attributes\DefaultPlan;

// For individual subscriptions
#[IndividualPlan]
class PersonalPlan extends BasePlan {}

// For team subscriptions
#[TeamPlan]
class TeamPlan extends BasePlan {}

// Mark as the default plan for unsubscribed users
#[DefaultPlan]
class FreePlan extends BasePlan {}
```

### Plan Visibility

```php
use ArtisanBuild\Till\Attributes\ArchivedPlan;

// Hide from pricing page but keep existing subscriptions
#[ArchivedPlan]
class LegacyPlan extends BasePlan {}
```

## UI Components

### Pricing Section

Till includes a Livewire component for displaying pricing:

```blade
<livewire:pricing-section />
```

This automatically:

- Loads all visible plans
- Displays pricing toggle (month/year/lifetime)
- Shows current plan badge for authenticated users
- Renders subscribe buttons with proper URLs

### Customizing the Pricing Display

Override the template in config:

```php
'pricing_section_template' => 'my-app::pricing.section',
```

Or publish and customize the default template:

```bash
php artisan vendor:publish --tag=till-views
```

## Payment Processors

### Demo Mode

The default Demo processor allows testing without actual payments:

```php
'payment_processor' => PaymentProcessors::Demo,
```

Demo mode:
- Doesn't process real payments
- Cannot be used in production
- Useful for local development and testing

### Stripe Integration

Enable Stripe processor:

```php
'payment_processor' => PaymentProcessors::Stripe,
```

Then sync your plans to Stripe:

```bash
php artisan till:sync-driver stripe
```

This creates Stripe products and prices based on your plan definitions.

## Team Mode vs Individual Mode

### Team Mode (Default)

When `team_mode` is `true`:

- Subscriptions belong to teams
- Team members share subscription benefits
- Use `current_team_id` to identify subscriber

### Individual Mode

When `team_mode` is `false`:

- Subscriptions belong to users
- Each user has their own subscription
- Use user `id` to identify subscriber

## Usage Examples

### Complete Plan Example

```php
namespace App\Plans;

use ArtisanBuild\Till\Attributes\TeamPlan;
use ArtisanBuild\Till\Enums\PlanTerms;
use ArtisanBuild\Till\SubscriptionPlans\BasePlan;
use App\Plans\Abilities\SendEmails;
use App\Plans\Abilities\AddTeamMembers;
use App\Plans\Abilities\AccessReports;

#[TeamPlan]
class BusinessPlan extends BasePlan
{
    public array $prices = [
        PlanTerms::Month->value => 99.00,
        PlanTerms::Year->value => 990.00,
        PlanTerms::Life->value => null, // Not offered
    ];

    public array $badge = [
        'text' => 'Most Popular',
        'color' => 'blue',
        'variant' => 'solid',
    ];

    public string $heading = 'Business';
    public string $subheading = 'Everything you need to scale';

    public array $features = [
        ['text' => 'Unlimited team members', 'icon' => 'users'],
        ['text' => '50,000 emails/month', 'icon' => 'envelope'],
        ['text' => 'Advanced analytics', 'icon' => 'chart-bar'],
        ['text' => 'Priority support', 'icon' => 'support'],
        ['text' => 'Custom integrations', 'icon' => 'puzzle-piece'],
    ];

    public array $can = [
        [AddTeamMembers::class, ['limit' => null]], // Unlimited
        [SendEmails::class, ['limit' => 50000]],
        [AccessReports::class, ['advanced' => true]],
    ];

    public array $wallet = [
        Ledgers::ApiCalls->value => [
            'balance' => 50000,
            'period' => LedgerPeriods::Monthly,
        ],
    ];
}
```

### Controller Example

```php
namespace App\Http\Controllers;

use ArtisanBuild\Till\Events\SubscriptionStarted;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function subscribe(Request $request, string $planId)
    {
        if (!auth()->user()->ableTo('change-plan')) {
            abort(403, 'You cannot change your plan');
        }

        // Process payment with your payment processor
        $payment = $this->processPayment($request, $planId);

        if ($payment->successful()) {
            SubscriptionStarted::fire(
                subscriber_id: auth()->user()->subscriberId(),
                plan_id: $planId,
                renews_at: now()->addMonth(),
            );

            return redirect()->route('dashboard')
                ->with('success', 'Subscription started successfully!');
        }

        return back()->with('error', 'Payment failed');
    }

    public function index()
    {
        $user = auth()->user();
        $subscription = $user->subscription();
        $abilities = $user->abilities();

        return view('subscriptions.index', [
            'currentPlan' => $subscription?->plan(),
            'abilities' => $abilities,
        ]);
    }
}
```

### Ability with Caching

```php
namespace App\Plans\Abilities;

use ArtisanBuild\Till\Actions\CacheAbility;

class SendEmails
{
    public function __invoke(?int $limit = null)
    {
        if ($limit === null) {
            return true;
        }

        $key = 'emails-sent-' . auth()->user()->subscriberId();
        $sent = cache()->remember($key, 3600, function () {
            return auth()->user()->emails()->thisMonth()->count();
        });

        return app(CacheAbility::class)(
            'send-emails',
            $sent < $limit
        );
    }
}
```

### Using Ledgers in Actions

```php
namespace App\Actions;

use ArtisanBuild\Till\Attributes\Costs;
use App\Plans\Ledgers;

#[Costs(ledger: Ledgers::ApiCalls, amount: 1)]
class ProcessApiRequest
{
    public function __invoke($endpoint, $data)
    {
        // Check if user has sufficient balance
        $state = auth()->user()->subscription();
        $balance = $state->wallet[Ledgers::ApiCalls->value] ?? 0;

        if ($balance < 1) {
            throw new \Exception('Insufficient API credits');
        }

        // Debit will happen automatically via #[Costs] attribute
        return $this->callApi($endpoint, $data);
    }

    protected function callApi($endpoint, $data)
    {
        // API call logic
    }
}
```

## Development Commands

### Code Quality

```bash
# Fix code style
composer lint

# Run static analysis
composer stan

# Run tests
composer test

# Run all quality checks
composer ready
```

### Testing

```bash
# Run tests
composer test

# Run with coverage
composer test-coverage

# Clean up after testing
php artisan till:cleanup-after-testing
```

## Advanced Features

### Custom Subscriber IDs

Override the `subscriberId()` method to use custom logic:

```php
public function subscriberId(): int
{
    return config('till.team_mode')
        ? $this->current_team_id
        : $this->id;
}
```

### Custom Plan Loading

Use the plan actions to load plans programmatically:

```php
use ArtisanBuild\Till\Actions\GetPlans;
use ArtisanBuild\Till\Actions\GetActivePlans;
use ArtisanBuild\Till\Actions\GetVisiblePlans;
use ArtisanBuild\Till\Actions\GetPlanById;
use ArtisanBuild\Till\Actions\GetDefaultPlan;

// Get all plans
$allPlans = app(GetPlans::class)();

// Get only active plans (not archived)
$activePlans = app(GetActivePlans::class)();

// Get visible plans (for pricing page)
$visiblePlans = app(GetVisiblePlans::class)();

// Get specific plan
$plan = app(GetPlanById::class)('pro-plan');

// Get the default plan
$defaultPlan = app(GetDefaultPlan::class)();
```

### Middleware

Protect routes based on abilities:

```php
// app/Http/Kernel.php
protected $middlewareAliases = [
    'till.abilities' => \ArtisanBuild\Till\Middleware\TillAbilities::class,
];

// routes/web.php
Route::middleware(['till.abilities:send-emails,access-reports'])
    ->group(function () {
        // Protected routes
    });
```

## Troubleshooting

### Abilities Not Updating

Clear the abilities cache:

```php
use ArtisanBuild\Till\Actions\ClearAbilitiesCache;

app(ClearAbilitiesCache::class)($subscriberId);
```

Or manually refresh:

```php
use ArtisanBuild\Till\Events\SubscriptionCacheUpdated;

SubscriptionCacheUpdated::commit(subscriber_id: $userId);
```

### Plans Not Loading

Ensure your plans:

1. Extend `BasePlan`
2. Are in the configured `plan_path`
3. Have the appropriate attributes (`#[TeamPlan]` or `#[IndividualPlan]`)
4. Implement the `PlanInterface`

### State Not Syncing

Manually trigger state synchronization:

```php
$processor = config('till.payment_processor');
$plan = auth()->user()->subscription()->plan();

if ($processor->sync($plan)) {
    // State was out of sync and has been corrected
}
```

## Roadmap

- [ ] Complete Stripe integration
- [ ] Paddle payment processor
- [ ] Webhook handling for payment processors
- [ ] Proration support
- [ ] Trial periods
- [ ] Coupon/discount system
- [ ] Invoice generation
- [ ] Usage-based billing automation
- [ ] Complete `TillAbilities` middleware implementation
- [ ] Complete `CreateAbilityCommand` implementation

## Testing

The package includes comprehensive tests using Pest:

```bash
composer test
```

## Contributing

Contributions are welcome! Please ensure all tests pass before submitting a pull request:

```bash
composer ready
```

## License

The MIT License (MIT). Please see the LICENSE file for more information.

## Credits

- [Artisan Build](https://github.com/artisan-build)
- Built on [Verbs](https://verbs.thunk.dev) event sourcing
- UI powered by [Flux UI](https://fluxui.dev)
- [All Contributors](../../contributors)
