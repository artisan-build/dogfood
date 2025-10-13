<p align="center"><img src="https://github.com/artisan-build/turbulence/raw/HEAD/art/turbulence.png" width="75%" alt="Artisan Build Package Turbulence Logo"></p>

# Turbulence

Scaffolding for organizational structures in Laravel applications

> [!WARNING]
> This package is currently under active development, and we have not yet released a major version. Once a 0.* version
> has been tagged, we strongly recommend locking your application to a specific working version because we might make
> breaking changes even in patch releases until we've tagged 1.0.

## Overview

Turbulence provides a flexible scaffolding system for building multi-account organizational structures in Laravel applications. It offers a clean separation between user authentication and account management, allowing users to belong to multiple accounts with different roles and permissions.

**Key Features:**

- Multi-account support with user role management
- Flexible organizational unit modeling
- Account session management with URL-based context switching
- Automated installation with Rector-based code transformation
- Profile extension system for accounts and users
- Built-in role-based permissions

## Requirements

- PHP 8.3+
- Laravel 11.36+ or Laravel 12+
- Verbs package (dev-main)

## Installation

Install the package via Composer:

```bash
composer require artisan-build/turbulence
```

### Running the Installer

Turbulence includes an automated installer that sets up your application:

```bash
php artisan turbulence:install
```

The installer will:

1. Install Rector (if not already installed)
2. Publish the configuration file
3. Copy base models (Account, AccountProfile) to your app
4. Add the `HasAccounts` trait to your User model
5. Add a `role` cast to your User model for the `UserRoles` enum
6. Add `current_account_id` column to your users table

If you need to run the installer again (for example, after making manual changes), use the `--force` flag:

```bash
php artisan turbulence:install --force
```

### Running Migrations

After installation, run the migrations to create the necessary database tables:

```bash
php artisan migrate
```

This creates:

- `profiles` table - User profile information
- `organizational_units` table - Flexible organizational structure
- Adds `current_account_id` and `role` columns to your users table

## Configuration

The configuration file is published to `config/turbulence.php`:

```php
return [
    'installed' => true,

    // Specify your User model
    'user_model' => \App\Models\User::class,

    // Specify your Account model
    'account_model' => \App\Models\Account::class,

    // Account session URL configuration
    'account_session_urls' => [
        // Enable URL-based account context switching
        'enabled' => false,

        // Index-based URL key (e.g., /u/0/dashboard)
        // Loads the first account from session('accounts')
        'index_key' => 'u',

        // ID-based URL key (e.g., /i/123/dashboard)
        // Loads account by ID (for impersonation)
        'id_key' => 'i',
    ],
];
```

## Core Concepts

### Accounts

Accounts are the primary organizational container in Turbulence. Users can belong to multiple accounts, and the `Account` model intentionally has minimal columns - just enough to associate it with a user.

```php
// The Account model provides the relationship to users
$account = Account::find(1);
$user = $account->user;
```

> **Important:** Do not add custom columns to the accounts table. Use the `AccountProfile` model instead.

### Account Profiles

Account profiles store all additional information about an account. This separation keeps the account structure clean and extensible:

```php
// Extend AccountProfile with your custom fields
class AccountProfile extends Model
{
    protected $fillable = ['company_name', 'industry', 'size'];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}
```

### User Accounts

Users can have multiple accounts and switch between them. The `HasAccounts` trait provides the necessary functionality:

```php
// Access user's accounts
$accounts = $user->accounts;

// Get current account
$currentAccount = $user->account;

// Switch to a different account
$user->switchAccount($account);

// Check if user owns an account
if ($user->ownsAccount($account)) {
    // Allow access
}
```

### User Roles

Turbulence includes a built-in role system with four predefined roles:

```php
use ArtisanBuild\Turbulence\Enums\UserRoles;

// Available roles
UserRoles::Owner       // Full control
UserRoles::SuperAdmin  // Can create admins
UserRoles::Admin       // Administrative access
UserRoles::User        // Standard user

// Role permissions
$user->role->canImpersonate();   // true for all except User
$user->role->canCreateAdmin();   // true for Owner and SuperAdmin
```

The installer automatically adds the `role` cast to your User model:

```php
protected $casts = [
    'role' => UserRoles::class,
];
```

### Organizational Units

Organizational units provide a flexible way to model hierarchical structures within accounts:

```php
use ArtisanBuild\Turbulence\Models\OrganizationalUnit;

// Extend the base class for your specific units
class Department extends OrganizationalUnit
{
    protected $table = 'organizational_units';

    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}

// Create organizational units
$department = Department::create([
    'account_id' => $account->id,
    'type' => 'department',
]);
```

## Account Session Management

Turbulence provides middleware for managing account context in your application.

### Basic Account Context

Add the middleware to routes that need account context:

```php
use ArtisanBuild\Turbulence\Middleware\AddAccountSessionToRequest;

Route::middleware([AddAccountSessionToRequest::class])
    ->group(function () {
        // Account is available in the request
        Route::get('/dashboard', function (Request $request) {
            $account = $request->account;
        });
    });
```

### URL-Based Account Switching

Enable URL-based account context in the configuration:

```php
'account_session_urls' => [
    'enabled' => true,
    'index_key' => 'u',  // /u/0/dashboard
    'id_key' => 'i',     // /i/123/dashboard
],
```

Configure routes with account identifiers:

```php
Route::middleware([SetDefaultsForAccountSessionUrls::class])
    ->prefix('{account_identifier}')
    ->group(function () {
        // Access via /u/0/dashboard (index-based)
        // or /i/123/dashboard (ID-based for impersonation)
        Route::get('/dashboard', DashboardController::class);
    });
```

The middleware will automatically load the appropriate account based on the URL:

- `/u/0/dashboard` - Loads the first account from `session('accounts')`
- `/i/301319658141229056/dashboard` - Loads account by ID (useful for impersonation)

## Usage Examples

### Creating an Account for a User

```php
use App\Models\Account;
use App\Models\AccountProfile;

$account = Account::create([
    'user_id' => $user->id,
]);

// Create the associated profile
AccountProfile::create([
    'account_id' => $account->id,
    'company_name' => 'Acme Corporation',
    'industry' => 'Technology',
]);

// Set as current account
$user->switchAccount($account);
```

### Multi-Account User Flow

```php
// User with multiple accounts
$user = User::find(1);

// List all accounts
foreach ($user->accounts as $account) {
    echo $account->profile->company_name;
}

// Switch between accounts
$targetAccount = $user->accounts()->where('id', 5)->first();

if ($user->switchAccount($targetAccount)) {
    // Successfully switched
    return redirect()->route('dashboard');
}
```

### Role-Based Authorization

```php
use ArtisanBuild\Turbulence\Enums\UserRoles;

// Check roles in controllers
public function impersonate(User $targetUser)
{
    if (!auth()->user()->role->canImpersonate()) {
        abort(403, 'You cannot impersonate users');
    }

    // Proceed with impersonation
}

// Use in blade templates
@if(auth()->user()->role === \ArtisanBuild\Turbulence\Enums\UserRoles::Owner)
    <a href="{{ route('admin.settings') }}">Admin Settings</a>
@endif

// Gate definitions
Gate::define('create-admin', function (User $user) {
    return $user->role->canCreateAdmin();
});
```

### Building Organizational Structures

```php
// Create custom organizational units
class Department extends OrganizationalUnit {}
class Team extends OrganizationalUnit {}
class Project extends OrganizationalUnit {}

// Build your structure
$engineering = Department::create([
    'account_id' => $account->id,
    'type' => 'department',
    'name' => 'Engineering',
]);

$backend = Team::create([
    'account_id' => $account->id,
    'type' => 'team',
    'name' => 'Backend Team',
    'parent_id' => $engineering->id,
]);
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
```

## Advanced Usage

### Customizing Models

You can extend the base models to add custom functionality:

```php
// app/Models/Account.php
namespace App\Models;

use ArtisanBuild\Turbulence\Models\Account as BaseAccount;

class Account extends BaseAccount
{
    public function profile()
    {
        return $this->hasOne(AccountProfile::class);
    }

    public function isActive(): bool
    {
        return $this->profile->subscription_status === 'active';
    }
}
```

### Account Session Storage

Store account session data for URL-based switching:

```php
// Store accounts in session (typically during login)
session(['accounts' => [
    ['id' => 1, 'name' => 'Personal Account'],
    ['id' => 2, 'name' => 'Work Account'],
]]);

// Load from session using AccountSession helper
use ArtisanBuild\Turbulence\Support\AccountSession;

// Load by index (for /u/0/ URLs)
$account = AccountSession::loadFromIndex(0);

// Load by ID (for /i/123/ URLs)
$account = AccountSession::load(123);
```

## Roadmap

- [ ] `turbulence:generate` command for scaffolding custom organizational units
- [ ] Additional relationship helpers for complex organizational structures
- [ ] Built-in permission system beyond basic roles
- [ ] Account invitation system
- [ ] Account transfer functionality

## Testing

The package includes a comprehensive test suite using Pest:

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
- [All Contributors](../../contributors)
