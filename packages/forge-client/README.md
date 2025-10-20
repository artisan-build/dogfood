<p align="center"><img src="https://github.com/artisan-build/forge-client/raw/HEAD/art/forge-client.png" width="75%" alt="Artisan Build Forge Client Logo"></p>

# Laravel Forge Client

A comprehensive, production-ready PHP SDK for the Laravel Forge API. Built with [Saloon](https://docs.saloon.dev/), this package provides a clean, strongly-typed interface for managing your Laravel Forge infrastructure programmatically.

> [!WARNING]
> This package is currently under active development. Once a 0.* version has been tagged, we strongly recommend locking your application to a specific working version because we might make breaking changes even in patch releases until we've tagged 1.0.

## Features

- 🚀 **Complete API Coverage** - All 132+ Laravel Forge API endpoints
- 🎯 **Strongly Typed** - PHP 8.3+ enums for all API values (server types, PHP versions, database types, etc.)
- 🛠️ **Atomic Artisan Commands** - Individual commands for each operation (create, list, destroy, etc.)
- 🔒 **Production Ready** - Confirmation prompts for destructive operations with `--dangerously-skip-confirmation` flag
- 📝 **Comprehensive Logging** - Configurable logging for all API operations and command executions
- ✅ **Laravel & Laravel Zero Compatible** - Minimal dependencies for maximum portability
- 🧪 **Fully Tested** - Comprehensive test suite with mocked API responses
- 📚 **Excellent Documentation** - Detailed examples for every resource and command

## Table of Contents

- [Installation](#installation)
- [Configuration](#configuration)
- [Quick Start](#quick-start)
- [Basic Usage](#basic-usage)
  - [Organizations](#organizations)
  - [Servers](#servers)
  - [Sites](#sites)
  - [Deployments](#deployments)
  - [Databases](#databases)
  - [Background Processes](#background-processes)
  - [Firewall Rules](#firewall-rules)
  - [SSL Certificates](#ssl-certificates)
  - [Commands](#commands)
  - [Scheduled Jobs](#scheduled-jobs)
  - [Other Resources](#other-resources)
- [Artisan Commands Reference](#artisan-commands-reference)
  - [Organization Commands](#organization-commands)
  - [Server Credential Commands](#server-credential-commands)
  - [Provider Commands](#provider-commands)
  - [Server Commands](#server-commands)
  - [Site Commands](#site-commands)
  - [Deployment Commands](#deployment-commands)
  - [Database Commands](#database-commands)
  - [Background Process Commands](#background-process-commands)
  - [Firewall Commands](#firewall-commands)
  - [SSL Certificate Commands](#ssl-certificate-commands)
- [Enums](#enums)
- [Error Handling](#error-handling)
- [Troubleshooting](#troubleshooting)
- [Contributing](#contributing)
- [License](#license)

## Installation

Install the package via Composer:

```bash
composer require artisan-build/forge-client
```

### Publish Configuration (Optional)

The package works out of the box, but you can publish the configuration file to customize settings:

```bash
php artisan vendor:publish --tag="forge-client-config"
```

This creates `config/forge-client.php` where you can configure API settings, logging, retry behavior, and defaults.

## Configuration

### Environment Variables

Set your Forge API token in your `.env` file:

```env
FORGE_API_TOKEN=your-forge-api-token-here
```

You can generate an API token from your [Forge account settings](https://forge.laravel.com/user-profile/api).

### Optional Configuration

```env
# Default organization (slug or ID) - optional
FORGE_ORGANIZATION=my-organization

# Default server (name or ID) - optional
FORGE_SERVER=my-server

# Server Creation Defaults - optional
FORGE_PHP_VERSION=php84
FORGE_DATABASE=mysql8

# API Configuration
FORGE_API_URL=https://forge.laravel.com/api/v1
FORGE_TIMEOUT=30

# Retry Configuration
FORGE_RETRY_TIMES=3
FORGE_RETRY_SLEEP=1000

# Logging Configuration
FORGE_LOG_CHANNEL=stack
FORGE_LOG_LEVEL=info
```

### Default Organization & Server

Setting default organization and server in your configuration simplifies commands:

```php
// config/forge-client.php
return [
    'default_organization' => env('FORGE_ORGANIZATION', 'my-org'),
    'default_server' => env('FORGE_SERVER', 'production-server'),
];
```

With defaults configured, you can run commands without specifying organization/server:

```bash
# Without defaults
php artisan forge:list-sites --server=my-server --organization=my-org

# With defaults configured
php artisan forge:list-sites
```

### Argument Ordering Principle

All commands follow a consistent argument ordering pattern: **most specific → least specific**.

This means resource-specific identifiers always come before broader context arguments:

```bash
# Correct: deployment → site → server → organization
php artisan forge:get-deployment {deployment} --site={site} --server={server} --organization={org}

# Correct: site → server → organization
php artisan forge:get-site {site} --server={server} --organization={org}

# Correct: database-user → server → organization
php artisan forge:get-database-user {user} --server={server} --organization={org}

# Correct: server → organization
php artisan forge:get-server {server} --organization={org}
```

This pattern makes commands intuitive and predictable across all resources. When both organization and server can have config defaults, the more specific resource (server) is still specified before the broader context (organization).

## Quick Start

### Using the SDK in Your Code

```php
use ArtisanBuild\ForgeClient\ForgeClient;

// Initialize the SDK
$forge = new ForgeClient();

// Authenticate with your API token
$forge->authenticate(config('forge-client.api_token'));

// List all organizations
$response = $forge->organizations()->organizationsIndex(
    sort: '-created_at',
    pagesize: 25,
    pagecursor: null
);

$organizations = $response->json('data');

foreach ($organizations as $org) {
    echo "Organization: {$org['name']} (ID: {$org['id']})\n";
}
```

### Using Artisan Commands

```bash
# List all organizations
php artisan forge:list-organizations

# Get a specific server (by ID or name)
php artisan forge:get-server 12345
php artisan forge:get-server production-server

# Create a new server (requires confirmation)
php artisan forge:create-server \
  --organization=my-org \
  --name=staging-server \
  --provider=ocean2 \
  --credential=123 \
  --size=456 \
  --region=nyc3

# Deploy a site (requires confirmation)
php artisan forge:deploy-site my-production-app

# Automated workflows (skip confirmation)
php artisan forge:deploy-site my-production-app --dangerously-skip-confirmation
```

## Basic Usage

### Organizations

Organizations are the top-level entity in Forge. All servers, sites, and resources belong to an organization.

#### List Organizations

```php
use ArtisanBuild\ForgeClient\ForgeClient;

$forge = new ForgeClient();
$forge->authenticate(config('forge-client.api_token'));

$response = $forge->organizations()->organizationsIndex(
    sort: '-created_at',
    pagesize: 25,
    pagecursor: null
);

$organizations = $response->json('data');
```

**Artisan Command:**

```bash
php artisan forge:list-organizations
```

#### Get Organization Details

```php
$response = $forge->organizations()->organizationsShow(
    organization: 'my-organization' // slug or ID
);

$organization = $response->json('data');
```

**Artisan Command:**

```bash
php artisan forge:get-organization my-organization
```

### Servers

Servers are the core infrastructure managed by Forge.

#### List Servers

```php
$response = $forge->servers()->organizationsServersIndex(
    organization: 'my-org',
    sort: '-created_at',
    pagesize: 25,
    pagecursor: null,
    filteripAddress: null,
    filtername: null,
    filterregion: null,
    filtersize: null,
    filterprovider: null,
    filterubuntuVersion: null,
    filterphpVersion: null,
    filterdatabaseType: null
);

$servers = $response->json('data');
```

**Artisan Command:**

```bash
# List all servers in organization
php artisan forge:list-servers --organization=my-org

# With filters
php artisan forge:list-servers \
  --organization=my-org \
  --filter-provider=ocean2 \
  --filter-region=nyc3
```

#### Get Server Details

```php
$response = $forge->servers()->organizationsServersShow(
    organization: 'my-org',
    server: 12345 // server ID
);

$server = $response->json('data');
```

**Artisan Command:**

```bash
# By ID
php artisan forge:get-server 12345 --organization=my-org

# By name (automatically resolved to ID)
php artisan forge:get-server production-server --organization=my-org
```

#### Create a Server

```php
use ArtisanBuild\ForgeClient\Enums\CloudProvider;
use ArtisanBuild\ForgeClient\Enums\ServerType;
use ArtisanBuild\ForgeClient\Enums\DatabaseType;
use ArtisanBuild\ForgeClient\Enums\PhpVersion;
use ArtisanBuild\ForgeClient\Enums\UbuntuVersion;

$response = $forge->servers()->organizationsServersStore(
    organization: 'my-org'
);

// Note: Body parameters are set on the request object
// Check the generated request class for available parameters
```

**Artisan Command:**

```bash
php artisan forge:create-server \
  --organization=my-org \
  --name=new-server \
  --provider=ocean2 \
  --credential=123 \
  --size=456 \
  --region=nyc3 \
  --type=app \
  --php-version=php84 \
  --database=mysql8 \
  --ubuntu-version=24.04
```

**Available Enums:**

- **CloudProvider:** `aws`, `ocean2`, `hetzner`, `vultr`, `akamai`, `laravel`, `custom`
- **ServerType:** `app`, `web`, `loadbalancer`, `database`, `cache`, `worker`, `meilisearch`
- **DatabaseType:** `mysql8`, `mysql`, `mariadb`, `postgres`, `none`
- **PhpVersion:** `php74`, `php80`, `php81`, `php82`, `php83`, `php84`, `php85`
- **UbuntuVersion:** `2204`, `2404`

#### Reboot a Server

```php
$response = $forge->servers()->organizationsServersActionsStore(
    organization: 'my-org',
    server: 12345
);
```

**Artisan Command:**

```bash
# Requires confirmation
php artisan forge:reboot-server 12345 --organization=my-org

# Skip confirmation for automation
php artisan forge:reboot-server 12345 \
  --organization=my-org \
  --dangerously-skip-confirmation
```

#### Delete a Server

```php
$response = $forge->servers()->organizationsServersDestroy(
    organization: 'my-org',
    server: 12345
);
```

**Artisan Command:**

```bash
# Requires confirmation (destructive operation)
php artisan forge:destroy-server 12345 --organization=my-org

# For automation
php artisan forge:destroy-server 12345 \
  --organization=my-org \
  --dangerously-skip-confirmation
```

### Sites

Sites represent the web applications hosted on your servers.

#### List Sites

```php
$response = $forge->sites()->organizationsServersSitesIndex(
    organization: 'my-org',
    server: 12345,
    sort: '-created_at',
    pagesize: 25,
    pagecursor: null,
    filteraliases: null,
    filtername: null
);

$sites = $response->json('data');
```

**Artisan Command:**

```bash
php artisan forge:list-sites \
  --organization=my-org \
  --server=12345
```

#### Get Site Details

```php
$response = $forge->sites()->organizationsServersSitesShow(
    organization: 'my-org',
    server: 12345,
    site: 67890
);

$site = $response->json('data');
```

**Artisan Command:**

```bash
php artisan forge:get-site 67890 \
  --organization=my-org \
  --server=12345
```

#### Create a Site

```php
$response = $forge->sites()->organizationsServersSitesStore(
    organization: 'my-org',
    server: 12345
);

// Body parameters set on request object
```

**Artisan Command:**

```bash
php artisan forge:create-site \
  --organization=my-org \
  --server=12345 \
  --domain=example.com \
  --project-type=php \
  --directory=/public
```

#### Update Site

```php
$response = $forge->sites()->organizationsServersSitesUpdate(
    organization: 'my-org',
    server: 12345,
    site: 67890
);
```

**Artisan Command:**

```bash
php artisan forge:update-site 67890 \
  --organization=my-org \
  --server=12345 \
  --directory=/public
```

#### Deploy Site

```php
$response = $forge->sites()->organizationsServersSitesActionsStore(
    organization: 'my-org',
    server: 12345,
    site: 67890
);
```

**Artisan Command:**

```bash
# Triggers deployment (requires confirmation)
php artisan forge:deploy-site 67890 \
  --organization=my-org \
  --server=12345
```

#### Enable/Disable Quick Deploy

```php
// Enable quick deploy
$response = $forge->sites()->organizationsServersSitesQuickDeployStore(
    organization: 'my-org',
    server: 12345,
    site: 67890
);

// Disable quick deploy
$response = $forge->sites()->organizationsServersSitesQuickDeployDestroy(
    organization: 'my-org',
    server: 12345,
    site: 67890
);
```

**Artisan Commands:**

```bash
# Enable quick deploy
php artisan forge:enable-quick-deploy 67890 \
  --organization=my-org \
  --server=12345

# Disable quick deploy
php artisan forge:disable-quick-deploy 67890 \
  --organization=my-org \
  --server=12345
```

#### Delete Site

```php
$response = $forge->sites()->organizationsServersSitesDestroy(
    organization: 'my-org',
    server: 12345,
    site: 67890
);
```

**Artisan Command:**

```bash
# Requires confirmation (destructive)
php artisan forge:destroy-site 67890 \
  --organization=my-org \
  --server=12345
```

### Deployments

Monitor and manage site deployments.

#### List Deployments

```php
$response = $forge->deployments()->organizationsServersSitesDeploymentsIndex(
    organization: 'my-org',
    server: 12345,
    site: 67890,
    sort: '-created_at',
    pagesize: 25,
    pagecursor: null
);

$deployments = $response->json('data');
```

**Artisan Command:**

```bash
php artisan forge:list-deployments 67890 \
  --organization=my-org \
  --server=12345
```

#### Get Deployment Details

```php
$response = $forge->deployments()->organizationsServersSitesDeploymentsShow(
    organization: 'my-org',
    server: 12345,
    site: 67890,
    deployment: 111213
);

$deployment = $response->json('data');
```

**Artisan Command:**

```bash
php artisan forge:get-deployment 111213 \
  --site=67890 \
  --organization=my-org \
  --server=12345
```

#### Trigger Deployment

```php
$response = $forge->deployments()->organizationsServersSitesDeploymentsStore(
    organization: 'my-org',
    server: 12345,
    site: 67890
);
```

**Artisan Command:**

```bash
# Requires confirmation
php artisan forge:trigger-deployment 67890 \
  --organization=my-org \
  --server=12345
```

#### Update Deployment Script

```php
$response = $forge->deployments()->organizationsServersSitesDeploymentsScriptsUpdate(
    organization: 'my-org',
    server: 12345,
    site: 67890
);

// Body contains the deployment script content
```

**Artisan Command:**

```bash
php artisan forge:update-deployment-script 67890 \
  --organization=my-org \
  --server=12345 \
  --script="cd /home/forge/site && git pull && php artisan migrate --force"
```

### Databases

Manage MySQL/PostgreSQL databases and users.

#### List Database Schemas

```php
$response = $forge->databases()->organizationsServersDatabasesIndex(
    organization: 'my-org',
    server: 12345,
    sort: '-created_at',
    pagesize: 25,
    pagecursor: null,
    filtername: null
);

$databases = $response->json('data');
```

**Artisan Command:**

```bash
php artisan forge:list-databases \
  --organization=my-org \
  --server=12345
```

#### Get Database Details

```php
$response = $forge->databases()->organizationsServersDatabasesShow(
    organization: 'my-org',
    server: 12345,
    database: 44556
);

$database = $response->json('data');
```

**Artisan Command:**

```bash
php artisan forge:get-database 44556 \
  --organization=my-org \
  --server=12345
```

#### Create Database

```php
$response = $forge->databases()->organizationsServersDatabasesStore(
    organization: 'my-org',
    server: 12345
);

// Body parameters include database name
```

**Artisan Command:**

```bash
php artisan forge:create-database \
  --organization=my-org \
  --server=12345 \
  --name=my_database
```

#### Delete Database

```php
$response = $forge->databases()->organizationsServersDatabasesDestroy(
    organization: 'my-org',
    server: 12345,
    database: 44556
);
```

**Artisan Command:**

```bash
# Requires confirmation (destructive)
php artisan forge:destroy-database 44556 \
  --organization=my-org \
  --server=12345
```

#### List Database Users

```php
$response = $forge->databases()->organizationsServersDatabaseUsersIndex(
    organization: 'my-org',
    server: 12345,
    sort: '-created_at',
    pagesize: 25,
    pagecursor: null,
    filtername: null
);

$users = $response->json('data');
```

**Artisan Command:**

```bash
php artisan forge:list-database-users \
  --organization=my-org \
  --server=12345
```

#### Create Database User

```php
$response = $forge->databases()->organizationsServersDatabaseUsersStore(
    organization: 'my-org',
    server: 12345
);

// Body parameters include username, password, and databases
```

**Artisan Command:**

```bash
php artisan forge:create-database-user \
  --organization=my-org \
  --server=12345 \
  --name=app_user \
  --password=secure_password \
  --databases=my_database
```

#### Update Database User

```php
$response = $forge->databases()->organizationsServersDatabaseUsersUpdate(
    organization: 'my-org',
    server: 12345,
    databaseUser: 77889
);
```

**Artisan Command:**

```bash
php artisan forge:update-database-user 77889 \
  --organization=my-org \
  --server=12345 \
  --databases=my_database,another_database
```

#### Delete Database User

```php
$response = $forge->databases()->organizationsServersDatabaseUsersDestroy(
    organization: 'my-org',
    server: 12345,
    databaseUser: 77889
);
```

**Artisan Command:**

```bash
# Requires confirmation (destructive)
php artisan forge:destroy-database-user 77889 \
  --organization=my-org \
  --server=12345
```

### Background Processes

Manage background processes (daemons) running on your servers.

#### List Background Processes

```php
$response = $forge->backgroundProcesses()->organizationsServersBackgroundProcessesIndex(
    organization: 'my-org',
    server: 12345,
    sort: '-created_at',
    pagesize: 25,
    pagecursor: null,
    filtercommand: null,
    filterstatus: null
);

$processes = $response->json('data');
```

**Artisan Command:**

```bash
php artisan forge:list-background-processes \
  --organization=my-org \
  --server=12345
```

#### Get Background Process Details

```php
$response = $forge->backgroundProcesses()->organizationsServersBackgroundProcessesShow(
    organization: 'my-org',
    server: 12345,
    backgroundProcess: 99887
);

$process = $response->json('data');
```

**Artisan Command:**

```bash
php artisan forge:get-background-process 99887 \
  --organization=my-org \
  --server=12345
```

#### Create Background Process

```php
$response = $forge->backgroundProcesses()->organizationsServersBackgroundProcessesStore(
    organization: 'my-org',
    server: 12345
);

// Body includes command, user, directory
```

**Artisan Command:**

```bash
php artisan forge:create-background-process \
  --organization=my-org \
  --server=12345 \
  --command="php artisan horizon" \
  --user=forge \
  --directory=/home/forge/app
```

#### Update Background Process

```php
$response = $forge->backgroundProcesses()->organizationsServersBackgroundProcessesUpdate(
    organization: 'my-org',
    server: 12345,
    backgroundProcess: 99887
);
```

**Artisan Command:**

```bash
php artisan forge:update-background-process 99887 \
  --organization=my-org \
  --server=12345 \
  --command="php artisan horizon"
```

#### Restart Background Process

```php
$response = $forge->backgroundProcesses()->organizationsServersBackgroundProcessesActionsStore(
    organization: 'my-org',
    server: 12345,
    backgroundProcess: 99887
);
```

**Artisan Command:**

```bash
php artisan forge:restart-background-process 99887 \
  --organization=my-org \
  --server=12345
```

#### Delete Background Process

```php
$response = $forge->backgroundProcesses()->organizationsServersBackgroundProcessesDestroy(
    organization: 'my-org',
    server: 12345,
    backgroundProcess: 99887
);
```

**Artisan Command:**

```bash
# Requires confirmation
php artisan forge:destroy-background-process 99887 \
  --organization=my-org \
  --server=12345
```

### Firewall Rules

Manage server firewall rules for security.

#### List Firewall Rules

```php
$response = $forge->firewallRules()->organizationsServersFirewallRulesIndex(
    organization: 'my-org',
    server: 12345,
    sort: '-created_at',
    pagesize: 25,
    pagecursor: null,
    filtername: null,
    filterport: null,
    filtertype: null
);

$rules = $response->json('data');
```

**Artisan Command:**

```bash
php artisan forge:list-firewall-rules \
  --organization=my-org \
  --server=12345
```

#### Get Firewall Rule Details

```php
$response = $forge->firewallRules()->organizationsServersFirewallRulesShow(
    organization: 'my-org',
    server: 12345,
    firewallRule: 55443
);

$rule = $response->json('data');
```

**Artisan Command:**

```bash
php artisan forge:get-firewall-rule 55443 \
  --organization=my-org \
  --server=12345
```

#### Create Firewall Rule

```php
use ArtisanBuild\ForgeClient\Enums\FirewallRuleType;

$response = $forge->firewallRules()->organizationsServersFirewallRulesStore(
    organization: 'my-org',
    server: 12345
);

// Body includes name, port, type, ip_address
```

**Artisan Command:**

```bash
php artisan forge:create-firewall-rule \
  --organization=my-org \
  --server=12345 \
  --name="Allow Redis" \
  --port=6379 \
  --type=allow \
  --ip-address=192.168.1.100
```

**Firewall Rule Types:**
- `allow` - Allow traffic
- `deny` - Deny traffic

#### Delete Firewall Rule

```php
$response = $forge->firewallRules()->organizationsServersFirewallRulesDestroy(
    organization: 'my-org',
    server: 12345,
    firewallRule: 55443
);
```

**Artisan Command:**

```bash
# Requires confirmation
php artisan forge:destroy-firewall-rule 55443 \
  --organization=my-org \
  --server=12345
```

### SSL Certificates

Manage SSL certificates for your sites.

#### List SSL Certificates

```php
$response = $forge->sites()->organizationsServersSitesCertificatesIndex(
    organization: 'my-org',
    server: 12345,
    site: 67890,
    sort: '-created_at',
    pagesize: 25,
    pagecursor: null,
    filterdomain: null
);

$certificates = $response->json('data');
```

**Artisan Command:**

```bash
php artisan forge:list-ssl-certificates 67890 \
  --organization=my-org \
  --server=12345
```

#### Get SSL Certificate Details

```php
$response = $forge->sites()->organizationsServersSitesCertificatesShow(
    organization: 'my-org',
    server: 12345,
    site: 67890,
    certificate: 22334
);

$certificate = $response->json('data');
```

**Artisan Command:**

```bash
php artisan forge:get-ssl-certificate 22334 \
  --site=67890 \
  --organization=my-org \
  --server=12345
```

#### Create SSL Certificate

```php
use ArtisanBuild\ForgeClient\Enums\CertificateType;

$response = $forge->sites()->organizationsServersSitesCertificatesStore(
    organization: 'my-org',
    server: 12345,
    site: 67890
);

// Body includes domain, type (letsencrypt, existing, clone)
```

**Artisan Command:**

```bash
# Let's Encrypt certificate
php artisan forge:create-ssl-certificate 67890 \
  --organization=my-org \
  --server=12345 \
  --domain=example.com \
  --type=letsencrypt

# Existing certificate
php artisan forge:create-ssl-certificate 67890 \
  --organization=my-org \
  --server=12345 \
  --type=existing \
  --certificate="$(cat certificate.crt)" \
  --key="$(cat private.key)"
```

#### Activate SSL Certificate

```php
$response = $forge->sites()->organizationsServersSitesCertificatesActionsStore(
    organization: 'my-org',
    server: 12345,
    site: 67890,
    certificate: 22334
);
```

**Artisan Command:**

```bash
php artisan forge:activate-ssl-certificate 22334 \
  --site=67890 \
  --organization=my-org \
  --server=12345
```

#### Delete SSL Certificate

```php
$response = $forge->sites()->organizationsServersSitesCertificatesDestroy(
    organization: 'my-org',
    server: 12345,
    site: 67890,
    certificate: 22334
);
```

**Artisan Command:**

```bash
# Requires confirmation
php artisan forge:destroy-ssl-certificate 22334 \
  --site=67890 \
  --organization=my-org \
  --server=12345
```

### Commands

The Commands resource represents one-off commands executed on servers (not to be confused with Artisan commands).

```php
// List server commands
$response = $forge->commands()->organizationsServersCommandsIndex(
    organization: 'my-org',
    server: 12345,
    sort: '-created_at',
    pagesize: 25,
    pagecursor: null
);
```

### Scheduled Jobs

Manage cron jobs on your servers.

```php
// List scheduled jobs
$response = $forge->scheduledJobs()->organizationsServersScheduledJobsIndex(
    organization: 'my-org',
    server: 12345,
    sort: '-created_at',
    pagesize: 25,
    pagecursor: null
);
```

### Other Resources

The SDK provides full access to all Forge API resources:

- **Integrations** - Source control providers (GitHub, GitLab, Bitbucket)
- **Logs** - Server and application logs
- **Monitors** - Server monitoring configuration
- **Nginx** - Nginx configuration templates
- **Providers** - Cloud provider credentials
- **Recipes** - Server provisioning recipes
- **Redirect Rules** - Site redirect/rewrite rules
- **Roles** - Organization role management
- **SSH Keys** - Server SSH key management
- **Security Rules** - Additional security configurations
- **Server Credentials** - Server access credentials
- **Teams** - Organization team management
- **User** - Current user information

All resources follow the same pattern as shown above. Check the resource classes in `src/Resource/` for available methods.

## Artisan Commands Reference

All commands support the `--dangerously-skip-confirmation` flag to bypass confirmation prompts for automation.

### Organization Commands

#### List Organizations

```bash
php artisan forge:list-organizations
```

**Options:**
- None

#### Get Organization

```bash
php artisan forge:get-organization {organization}
```

**Arguments:**
- `organization` - Organization slug or ID

**Example:**
```bash
php artisan forge:get-organization my-organization
```

### Server Credential Commands

#### List Server Credentials

```bash
php artisan forge:list-server-credentials {organization?}
```

**Arguments:**
- `organization` - Organization slug or ID (optional if `FORGE_ORGANIZATION` is set)

**Options:**
- `--pagesize=` - Number of results per page
- `--pagecursor=` - Cursor for pagination

**Example:**
```bash
# Using organization argument
php artisan forge:list-server-credentials my-organization

# Or using environment variable
export FORGE_ORGANIZATION="my-organization"
php artisan forge:list-server-credentials
```

This command lists all server credentials (cloud provider API keys) configured for your organization. You'll need the credential ID when creating servers.

### Provider Commands

#### List Providers

```bash
php artisan forge:list-providers
```

**Options:**
- `--pagesize=` - Number of results per page
- `--pagecursor=` - Cursor for pagination

**Example:**
```bash
php artisan forge:list-providers
```

#### List Provider Regions

```bash
php artisan forge:list-provider-regions {provider}
```

**Arguments:**
- `provider` - Provider ID

**Options:**
- `--pagesize=` - Number of results per page
- `--pagecursor=` - Cursor for pagination

**Example:**
```bash
# First, get the provider ID
php artisan forge:list-providers

# Then list regions for that provider
php artisan forge:list-provider-regions 1
```

#### List Provider Sizes

```bash
php artisan forge:list-provider-sizes {provider}
```

**Arguments:**
- `provider` - Provider ID

**Options:**
- `--pagesize=` - Number of results per page
- `--pagecursor=` - Cursor for pagination

**Example:**
```bash
# First, get the provider ID
php artisan forge:list-providers

# Then list sizes for that provider
php artisan forge:list-provider-sizes 1
```

### Server Commands

#### List Servers

```bash
php artisan forge:list-servers
```

**Options:**
- `--organization=` - Organization slug or ID (optional if default configured)
- `--filter-name=` - Filter by server name
- `--filter-provider=` - Filter by cloud provider
- `--filter-region=` - Filter by region
- `--filter-size=` - Filter by server size
- `--filter-ip-address=` - Filter by IP address
- `--filter-ubuntu-version=` - Filter by Ubuntu version
- `--filter-php-version=` - Filter by PHP version
- `--filter-database-type=` - Filter by database type

**Example:**
```bash
php artisan forge:list-servers \
  --organization=my-org \
  --filter-provider=ocean2 \
  --filter-region=nyc3
```

#### Get Server

```bash
php artisan forge:get-server {server}
```

**Arguments:**
- `server` - Server ID or name

**Options:**
- `--organization=` - Organization slug or ID (optional if default configured)

**Example:**
```bash
# By ID
php artisan forge:get-server 12345 --organization=my-org

# By name
php artisan forge:get-server production-server --organization=my-org
```

#### Create Server

```bash
php artisan forge:create-server
```

**Options:**
- `--organization=` - Organization slug or ID (required unless set in config via `FORGE_ORGANIZATION`)
- `--name=` - Server name (required)
- `--provider=` - Cloud provider: laravel, ocean2, hetzner, vultr, akamai, aws, custom (required)
- `--credential=` - Server credential ID (required - use `forge:list-server-credentials` to find IDs)
- `--size=` - Server size ID (required - use `forge:list-provider-sizes` to find IDs)
- `--region=` - Region code (required - use `forge:list-provider-regions` to find codes)
- `--type=` - Server type: app, web, database, cache, worker, meilisearch, scheduler, loadbalancer (default: app)
- `--ubuntu-version=` - Ubuntu version: 22.04, 24.04 (default: 24.04)
- `--php-version=` - PHP version: php81, php82, php83, php84 (optional - defaults to `FORGE_PHP_VERSION` if set)
- `--database=` - Database type: mysql8, postgres, mariadb, none (optional - defaults to `FORGE_DATABASE` if set)
- `--dangerously-skip-confirmation` - Skip confirmation prompt (for automation)

**Prerequisites:**
Before creating a server, you need to:
1. Get a credential ID: `php artisan forge:list-server-credentials --organization=my-org`
2. Get a provider ID: `php artisan forge:list-providers`
3. Get a region code: `php artisan forge:list-provider-regions {provider-id}`
4. Get a size ID: `php artisan forge:list-provider-sizes {provider-id}`

**Example:**
```bash
# Create a DigitalOcean server with explicit options
php artisan forge:create-server \
  --organization=my-org \
  --name=staging-server \
  --provider=ocean2 \
  --credential=123 \
  --region=nyc3 \
  --size=456 \
  --type=app \
  --php-version=php84 \
  --database=mysql8 \
  --ubuntu-version=24.04

# Create a Laravel-managed server using config defaults
# (Assuming FORGE_PHP_VERSION=php84 and FORGE_DATABASE=mysql8 in .env)
php artisan forge:create-server \
  --organization=my-org \
  --name=laravel-server \
  --provider=laravel \
  --credential=789 \
  --region=us-east \
  --size=1
```

#### Reboot Server

```bash
php artisan forge:reboot-server {server}
```

**Arguments:**
- `server` - Server ID or name

**Options:**
- `--organization=` - Organization slug or ID (optional if default configured)
- `--dangerously-skip-confirmation` - Skip confirmation prompt

**Example:**
```bash
# With confirmation
php artisan forge:reboot-server production-server

# Skip confirmation
php artisan forge:reboot-server production-server --dangerously-skip-confirmation
```

#### Destroy Server

```bash
php artisan forge:destroy-server {server}
```

**Arguments:**
- `server` - Server ID or name

**Options:**
- `--organization=` - Organization slug or ID (optional if default configured)
- `--dangerously-skip-confirmation` - Skip confirmation prompt

**Example:**
```bash
php artisan forge:destroy-server old-server --dangerously-skip-confirmation
```

### Site Commands

#### List Sites

```bash
php artisan forge:list-sites
```

**Options:**
- `--organization=` - Organization slug or ID (optional if default configured)
- `--server=` - Server ID or name (optional if default configured)
- `--filter-name=` - Filter by site name
- `--filter-aliases=` - Filter by site aliases

#### Get Site

```bash
php artisan forge:get-site {site}
```

**Arguments:**
- `site` - Site ID or domain name

**Options:**
- `--organization=` - Organization slug or ID (optional if default configured)
- `--server=` - Server ID or name (optional if default configured)

#### Create Site

```bash
php artisan forge:create-site
```

**Options:**
- `--organization=` - Organization slug or ID (required)
- `--server=` - Server ID or name (required)
- `--domain=` - Domain name (required)
- `--project-type=` - Project type: laravel, symfony, statamic, wordpress, phpmyadmin, php, next.js, nuxt.js, static-html, other, custom (optional)
- `--directory=` - Web directory, e.g., /public (optional)

**Example:**
```bash
php artisan forge:create-site \
  --organization=my-org \
  --server=production-server \
  --domain=example.com \
  --project-type=php \
  --directory=/public
```

#### Update Site

```bash
php artisan forge:update-site {site}
```

**Arguments:**
- `site` - Site ID or domain name

**Options:**
- `--organization=` - Organization slug or ID (required)
- `--server=` - Server ID or name (required)
- `--directory=` - Update web directory

#### Deploy Site

```bash
php artisan forge:deploy-site {site}
```

**Arguments:**
- `site` - Site ID or domain name

**Options:**
- `--organization=` - Organization slug or ID (optional if default configured)
- `--server=` - Server ID or name (optional if default configured)
- `--dangerously-skip-confirmation` - Skip confirmation prompt

**Example:**
```bash
php artisan forge:deploy-site example.com --dangerously-skip-confirmation
```

#### Enable Quick Deploy

```bash
php artisan forge:enable-quick-deploy {site}
```

**Arguments:**
- `site` - Site ID or domain name

**Options:**
- `--organization=` - Organization slug or ID (optional if default configured)
- `--server=` - Server ID or name (optional if default configured)

#### Disable Quick Deploy

```bash
php artisan forge:disable-quick-deploy {site}
```

**Arguments:**
- `site` - Site ID or domain name

**Options:**
- `--organization=` - Organization slug or ID (optional if default configured)
- `--server=` - Server ID or name (optional if default configured)

#### Destroy Site

```bash
php artisan forge:destroy-site {site}
```

**Arguments:**
- `site` - Site ID or domain name

**Options:**
- `--organization=` - Organization slug or ID (optional if default configured)
- `--server=` - Server ID or name (optional if default configured)
- `--dangerously-skip-confirmation` - Skip confirmation prompt

### Deployment Commands

#### List Deployments

```bash
php artisan forge:list-deployments {site}
```

**Arguments:**
- `site` - Site ID or domain name

**Options:**
- `--organization=` - Organization slug or ID (optional if default configured)
- `--server=` - Server ID or name (optional if default configured)

#### Get Deployment

```bash
php artisan forge:get-deployment {deployment}
```

**Arguments:**
- `deployment` - Deployment ID

**Options:**
- `--site=` - Site ID or domain name (required)
- `--organization=` - Organization slug or ID (optional if default configured)
- `--server=` - Server ID or name (optional if default configured)

#### Trigger Deployment

```bash
php artisan forge:trigger-deployment {site}
```

**Arguments:**
- `site` - Site ID or domain name

**Options:**
- `--organization=` - Organization slug or ID (optional if default configured)
- `--server=` - Server ID or name (optional if default configured)
- `--dangerously-skip-confirmation` - Skip confirmation prompt

#### Update Deployment Script

```bash
php artisan forge:update-deployment-script {site}
```

**Arguments:**
- `site` - Site ID or domain name

**Options:**
- `--organization=` - Organization slug or ID (optional if default configured)
- `--server=` - Server ID or name (optional if default configured)
- `--script=` - Deployment script content (required)

**Example:**
```bash
php artisan forge:update-deployment-script example.com \
  --script="cd /home/forge/example.com && git pull origin main && composer install --no-dev && php artisan migrate --force"
```

### Database Commands

#### List Databases

```bash
php artisan forge:list-databases
```

**Options:**
- `--organization=` - Organization slug or ID (optional if default configured)
- `--server=` - Server ID or name (optional if default configured)
- `--filter-name=` - Filter by database name

#### Get Database

```bash
php artisan forge:get-database {database}
```

**Arguments:**
- `database` - Database ID or name

**Options:**
- `--organization=` - Organization slug or ID (optional if default configured)
- `--server=` - Server ID or name (optional if default configured)

#### Create Database

```bash
php artisan forge:create-database
```

**Options:**
- `--organization=` - Organization slug or ID (required)
- `--server=` - Server ID or name (required)
- `--name=` - Database name (required)

**Example:**
```bash
php artisan forge:create-database \
  --organization=my-org \
  --server=production-server \
  --name=my_application_db
```

#### Destroy Database

```bash
php artisan forge:destroy-database {database}
```

**Arguments:**
- `database` - Database ID or name

**Options:**
- `--organization=` - Organization slug or ID (optional if default configured)
- `--server=` - Server ID or name (optional if default configured)
- `--dangerously-skip-confirmation` - Skip confirmation prompt

#### List Database Users

```bash
php artisan forge:list-database-users
```

**Options:**
- `--organization=` - Organization slug or ID (optional if default configured)
- `--server=` - Server ID or name (optional if default configured)
- `--filter-name=` - Filter by username

#### Get Database User

```bash
php artisan forge:get-database-user {user}
```

**Arguments:**
- `user` - Database user ID or username

**Options:**
- `--organization=` - Organization slug or ID (optional if default configured)
- `--server=` - Server ID or name (optional if default configured)

#### Create Database User

```bash
php artisan forge:create-database-user
```

**Options:**
- `--organization=` - Organization slug or ID (required)
- `--server=` - Server ID or name (required)
- `--name=` - Username (required)
- `--password=` - Password (required)
- `--databases=` - Comma-separated list of database names to grant access (required)

**Example:**
```bash
php artisan forge:create-database-user \
  --organization=my-org \
  --server=production-server \
  --name=app_user \
  --password=secure_password_here \
  --databases=my_app_db,my_app_cache
```

#### Update Database User

```bash
php artisan forge:update-database-user {user}
```

**Arguments:**
- `user` - Database user ID or username

**Options:**
- `--organization=` - Organization slug or ID (required)
- `--server=` - Server ID or name (required)
- `--databases=` - Comma-separated list of database names to grant access (required)

#### Destroy Database User

```bash
php artisan forge:destroy-database-user {user}
```

**Arguments:**
- `user` - Database user ID or username

**Options:**
- `--organization=` - Organization slug or ID (optional if default configured)
- `--server=` - Server ID or name (optional if default configured)
- `--dangerously-skip-confirmation` - Skip confirmation prompt

### Background Process Commands

#### List Background Processes

```bash
php artisan forge:list-background-processes
```

**Options:**
- `--organization=` - Organization slug or ID (optional if default configured)
- `--server=` - Server ID or name (optional if default configured)
- `--filter-command=` - Filter by command
- `--filter-status=` - Filter by status

#### Get Background Process

```bash
php artisan forge:get-background-process {process}
```

**Arguments:**
- `process` - Background process ID

**Options:**
- `--organization=` - Organization slug or ID (optional if default configured)
- `--server=` - Server ID or name (optional if default configured)

#### Create Background Process

```bash
php artisan forge:create-background-process
```

**Options:**
- `--organization=` - Organization slug or ID (required)
- `--server=` - Server ID or name (required)
- `--command=` - Command to run (required)
- `--user=` - User to run as (optional, defaults to forge)
- `--directory=` - Working directory (optional)

**Example:**
```bash
php artisan forge:create-background-process \
  --organization=my-org \
  --server=production-server \
  --command="php artisan horizon" \
  --user=forge \
  --directory=/home/forge/example.com
```

#### Update Background Process

```bash
php artisan forge:update-background-process {process}
```

**Arguments:**
- `process` - Background process ID

**Options:**
- `--organization=` - Organization slug or ID (required)
- `--server=` - Server ID or name (required)
- `--command=` - Updated command (required)

#### Restart Background Process

```bash
php artisan forge:restart-background-process {process}
```

**Arguments:**
- `process` - Background process ID

**Options:**
- `--organization=` - Organization slug or ID (optional if default configured)
- `--server=` - Server ID or name (optional if default configured)
- `--dangerously-skip-confirmation` - Skip confirmation prompt

#### Destroy Background Process

```bash
php artisan forge:destroy-background-process {process}
```

**Arguments:**
- `process` - Background process ID

**Options:**
- `--organization=` - Organization slug or ID (optional if default configured)
- `--server=` - Server ID or name (optional if default configured)
- `--dangerously-skip-confirmation` - Skip confirmation prompt

### Firewall Commands

#### List Firewall Rules

```bash
php artisan forge:list-firewall-rules
```

**Options:**
- `--organization=` - Organization slug or ID (optional if default configured)
- `--server=` - Server ID or name (optional if default configured)
- `--filter-name=` - Filter by rule name
- `--filter-port=` - Filter by port
- `--filter-type=` - Filter by type (allow/deny)

#### Get Firewall Rule

```bash
php artisan forge:get-firewall-rule {rule}
```

**Arguments:**
- `rule` - Firewall rule ID

**Options:**
- `--organization=` - Organization slug or ID (optional if default configured)
- `--server=` - Server ID or name (optional if default configured)

#### Create Firewall Rule

```bash
php artisan forge:create-firewall-rule
```

**Options:**
- `--organization=` - Organization slug or ID (required)
- `--server=` - Server ID or name (required)
- `--name=` - Rule name (optional)
- `--port=` - Port number (required)
- `--type=` - Rule type: allow, deny (required)
- `--ip-address=` - IP address to allow/deny (optional, defaults to 0.0.0.0/0)

**Example:**
```bash
php artisan forge:create-firewall-rule \
  --organization=my-org \
  --server=production-server \
  --name="Allow Redis from app server" \
  --port=6379 \
  --type=allow \
  --ip-address=192.168.1.100
```

#### Destroy Firewall Rule

```bash
php artisan forge:destroy-firewall-rule {rule}
```

**Arguments:**
- `rule` - Firewall rule ID

**Options:**
- `--organization=` - Organization slug or ID (optional if default configured)
- `--server=` - Server ID or name (optional if default configured)
- `--dangerously-skip-confirmation` - Skip confirmation prompt

### SSL Certificate Commands

#### List SSL Certificates

```bash
php artisan forge:list-ssl-certificates {site}
```

**Arguments:**
- `site` - Site ID or domain name

**Options:**
- `--organization=` - Organization slug or ID (optional if default configured)
- `--server=` - Server ID or name (optional if default configured)
- `--filter-domain=` - Filter by domain

#### Get SSL Certificate

```bash
php artisan forge:get-ssl-certificate {certificate}
```

**Arguments:**
- `certificate` - SSL certificate ID

**Options:**
- `--site=` - Site ID or domain name (required)
- `--organization=` - Organization slug or ID (optional if default configured)
- `--server=` - Server ID or name (optional if default configured)

#### Create SSL Certificate

```bash
php artisan forge:create-ssl-certificate {site}
```

**Arguments:**
- `site` - Site ID or domain name

**Options:**
- `--organization=` - Organization slug or ID (required)
- `--server=` - Server ID or name (required)
- `--domain=` - Domain name (required)
- `--type=` - Certificate type: letsencrypt, existing, clone (required)
- `--certificate=` - Certificate content (required if type=existing)
- `--key=` - Private key content (required if type=existing)

**Example:**
```bash
# Let's Encrypt
php artisan forge:create-ssl-certificate example.com \
  --organization=my-org \
  --server=production-server \
  --domain=example.com \
  --type=letsencrypt

# Existing certificate
php artisan forge:create-ssl-certificate example.com \
  --organization=my-org \
  --server=production-server \
  --type=existing \
  --certificate="$(cat /path/to/certificate.crt)" \
  --key="$(cat /path/to/private.key)"
```

#### Activate SSL Certificate

```bash
php artisan forge:activate-ssl-certificate {certificate}
```

**Arguments:**
- `certificate` - SSL certificate ID

**Options:**
- `--site=` - Site ID or domain name (required)
- `--organization=` - Organization slug or ID (optional if default configured)
- `--server=` - Server ID or name (optional if default configured)
- `--dangerously-skip-confirmation` - Skip confirmation prompt

#### Destroy SSL Certificate

```bash
php artisan forge:destroy-ssl-certificate {certificate}
```

**Arguments:**
- `certificate` - SSL certificate ID

**Options:**
- `--site=` - Site ID or domain name (required)
- `--organization=` - Organization slug or ID (optional if default configured)
- `--server=` - Server ID or name (optional if default configured)
- `--dangerously-skip-confirmation` - Skip confirmation prompt

## Enums

The SDK provides strongly-typed enums for all Forge API values, ensuring type safety and preventing invalid API requests.

### Available Enums

#### CloudProvider

```php
use ArtisanBuild\ForgeClient\Enums\CloudProvider;

CloudProvider::AWS;      // 'aws'
CloudProvider::OCEAN2;   // 'ocean2' (DigitalOcean)
CloudProvider::HETZNER;  // 'hetzner'
CloudProvider::VULTR;    // 'vultr'
CloudProvider::AKAMAI;   // 'akamai' (Linode/Akamai)
CloudProvider::LARAVEL;  // 'laravel' (Laravel Cloud)
CloudProvider::CUSTOM;   // 'custom'
```

**Helper Methods:**
```php
CloudProvider::OCEAN2->label();       // "DigitalOcean"
CloudProvider::OCEAN2->description(); // "DigitalOcean cloud hosting"
CloudProvider::isValid('ocean2');     // true
CloudProvider::options();             // Array of all options
```

#### ServerType

```php
use ArtisanBuild\ForgeClient\Enums\ServerType;

ServerType::APP;          // 'app'
ServerType::WEB;          // 'web'
ServerType::LOADBALANCER; // 'loadbalancer'
ServerType::DATABASE;     // 'database'
ServerType::CACHE;        // 'cache'
ServerType::WORKER;       // 'worker'
ServerType::MEILISEARCH;  // 'meilisearch'
```

#### DatabaseType

```php
use ArtisanBuild\ForgeClient\Enums\DatabaseType;

DatabaseType::MYSQL8;   // 'mysql8'
DatabaseType::MYSQL;    // 'mysql'
DatabaseType::MARIADB;  // 'mariadb'
DatabaseType::POSTGRES; // 'postgres'
DatabaseType::NONE;     // 'none'
```

**Helper Methods:**
```php
DatabaseType::MYSQL8->label();              // "MySQL 8"
DatabaseType::MYSQL8->description();        // "MySQL 8.0"
DatabaseType::MYSQL8->isMySQL();           // true
DatabaseType::POSTGRES->isPostgreSQL();    // true
```

#### PhpVersion

```php
use ArtisanBuild\ForgeClient\Enums\PhpVersion;

PhpVersion::PHP74; // 'php74'
PhpVersion::PHP80; // 'php80'
PhpVersion::PHP81; // 'php81'
PhpVersion::PHP82; // 'php82'
PhpVersion::PHP83; // 'php83'
PhpVersion::PHP84; // 'php84'
PhpVersion::PHP85; // 'php85'
```

**Helper Methods:**
```php
PhpVersion::PHP84->label();              // "PHP 8.4"
PhpVersion::PHP84->version();            // "8.4"
PhpVersion::PHP84->isSupported();        // true
PhpVersion::latest();                    // PhpVersion::PHP85
PhpVersion::supported();                 // Array of supported versions
```

#### UbuntuVersion

```php
use ArtisanBuild\ForgeClient\Enums\UbuntuVersion;

UbuntuVersion::UBUNTU_22_04; // '2204'
UbuntuVersion::UBUNTU_24_04; // '2404'
```

**Helper Methods:**
```php
UbuntuVersion::UBUNTU_24_04->label();       // "Ubuntu 24.04"
UbuntuVersion::UBUNTU_24_04->version();     // "24.04"
UbuntuVersion::UBUNTU_24_04->isLTS();       // true
UbuntuVersion::latest();                    // UbuntuVersion::UBUNTU_24_04
```

#### FirewallRuleType

```php
use ArtisanBuild\ForgeClient\Enums\FirewallRuleType;

FirewallRuleType::ALLOW; // 'allow'
FirewallRuleType::DENY;  // 'deny'
```

#### CertificateType

```php
use ArtisanBuild\ForgeClient\Enums\CertificateType;

CertificateType::LETSENCRYPT; // 'letsencrypt'
CertificateType::EXISTING;    // 'existing'
CertificateType::CLONE;       // 'clone'
```

#### SiteType

```php
use ArtisanBuild\ForgeClient\Enums\SiteType;

SiteType::LARAVEL;     // 'laravel'
SiteType::SYMFONY;     // 'symfony'
SiteType::STATAMIC;    // 'statamic'
SiteType::WORDPRESS;   // 'wordpress'
SiteType::PHPMYADMIN;  // 'phpmyadmin'
SiteType::PHP;         // 'php'
SiteType::NEXTJS;      // 'next.js'
SiteType::NUXTJS;      // 'nuxt.js'
SiteType::STATIC_HTML; // 'static-html'
SiteType::OTHER;       // 'other'
SiteType::CUSTOM;      // 'custom'
```

#### IntegrationType

```php
use ArtisanBuild\ForgeClient\Enums\IntegrationType;

IntegrationType::GITHUB;    // 'github'
IntegrationType::GITLAB;    // 'gitlab'
IntegrationType::BITBUCKET; // 'bitbucket'
```

#### JobFrequency

```php
use ArtisanBuild\ForgeClient\Enums\JobFrequency;

JobFrequency::MINUTELY; // 'minutely'
JobFrequency::HOURLY;   // 'hourly'
JobFrequency::NIGHTLY;  // 'nightly'
JobFrequency::WEEKLY;   // 'weekly'
JobFrequency::MONTHLY;  // 'monthly'
JobFrequency::CUSTOM;   // 'custom'
```

#### LogType

```php
use ArtisanBuild\ForgeClient\Enums\LogType;

LogType::APP;    // 'app'
LogType::NGINX;  // 'nginx'
LogType::PHP;    // 'php'
LogType::MYSQL;  // 'mysql'
```

#### MonitorType

```php
use ArtisanBuild\ForgeClient\Enums\MonitorType;

MonitorType::CPU;        // 'cpu'
MonitorType::MEMORY;     // 'memory'
MonitorType::DISK;       // 'disk'
MonitorType::CUSTOM;     // 'custom'
```

### Using Enums

Enums provide type safety and validation:

```php
use ArtisanBuild\ForgeClient\Enums\CloudProvider;
use ArtisanBuild\ForgeClient\Enums\PhpVersion;
use ArtisanBuild\ForgeClient\Enums\DatabaseType;

// In your code
$provider = CloudProvider::OCEAN2;
$phpVersion = PhpVersion::PHP84;
$database = DatabaseType::MYSQL8;

// Validation
if (CloudProvider::isValid('ocean2')) {
    // Valid provider
}

// Get all options for dropdowns
$providers = CloudProvider::options();
// Returns: ['ocean2' => 'DigitalOcean', 'akamai' => 'Akamai', ...]

// Get latest/recommended versions
$latestPhp = PhpVersion::latest(); // PHP 8.5
$supportedPhp = PhpVersion::supported(); // All supported versions
```

## Error Handling

The SDK provides comprehensive exception handling with detailed error messages.

### Exception Types

#### ForgeException

Base exception for all Forge Client errors.

```php
use ArtisanBuild\ForgeClient\Exceptions\ForgeException;

try {
    $response = $forge->servers()->organizationsServersShow('my-org', 12345);
} catch (ForgeException $e) {
    echo "Forge Error: {$e->getMessage()}";
    echo "Context: " . json_encode($e->getContext());
}
```

**Methods:**
- `getMessage()` - Error message
- `getContext()` - Additional error context (array)
- `getCode()` - Error code

#### ValidationException

Thrown when request parameters fail validation before the API call.

```php
use ArtisanBuild\ForgeClient\Exceptions\ValidationException;

try {
    // Invalid PHP version
    $response = $forge->servers()->organizationsServersStore('my-org');
} catch (ValidationException $e) {
    echo "Validation Error: {$e->getMessage()}";
    echo "Failed Field: {$e->getField()}";
    echo "Invalid Value: {$e->getValue()}";
}
```

**Methods:**
- `getField()` - Field that failed validation
- `getValue()` - Invalid value provided
- `getExpected()` - Expected value format/type

#### ApiException

Thrown when the Forge API returns an error response.

```php
use ArtisanBuild\ForgeClient\Exceptions\ApiException;

try {
    $response = $forge->servers()->organizationsServersShow('my-org', 99999);
} catch (ApiException $e) {
    echo "API Error: {$e->getMessage()}";
    echo "Status Code: {$e->getStatusCode()}";
    echo "Response: " . json_encode($e->getResponseData());
}
```

**Methods:**
- `getStatusCode()` - HTTP status code (404, 500, etc.)
- `getResponseData()` - Full API response body
- `getErrorCode()` - Forge-specific error code

#### AuthenticationException

Thrown when API authentication fails.

```php
use ArtisanBuild\ForgeClient\Exceptions\AuthenticationException;

try {
    $forge->authenticate('invalid-token');
    $response = $forge->organizations()->organizationsIndex();
} catch (AuthenticationException $e) {
    echo "Authentication Error: {$e->getMessage()}";
    echo "Troubleshooting:\n";
    echo "1. Check your API token in .env\n";
    echo "2. Verify token is active in Forge dashboard\n";
    echo "3. Ensure token has required permissions\n";
}
```

#### RateLimitException

Thrown when Forge API rate limit is exceeded (60 requests per minute).

```php
use ArtisanBuild\ForgeClient\Exceptions\RateLimitException;

try {
    $response = $forge->servers()->organizationsServersIndex('my-org');
} catch (RateLimitException $e) {
    echo "Rate Limit Exceeded: {$e->getMessage()}";
    echo "Retry After: {$e->getRetryAfter()} seconds";

    // Wait and retry
    sleep($e->getRetryAfter());
    $response = $forge->servers()->organizationsServersIndex('my-org');
}
```

**Methods:**
- `getRetryAfter()` - Seconds until rate limit resets
- `getLimit()` - Rate limit maximum (60)
- `getRemaining()` - Remaining requests

### Error Handling Patterns

#### Comprehensive Try-Catch

```php
use ArtisanBuild\ForgeClient\Exceptions\{
    ValidationException,
    AuthenticationException,
    RateLimitException,
    ApiException,
    ForgeException
};

try {
    $response = $forge->servers()->organizationsServersShow('my-org', $serverId);
    $server = $response->json('data');
} catch (ValidationException $e) {
    // Handle validation errors (before API call)
    Log::error('Invalid parameters', [
        'field' => $e->getField(),
        'value' => $e->getValue(),
    ]);
} catch (AuthenticationException $e) {
    // Handle authentication failures
    Log::error('Authentication failed', ['message' => $e->getMessage()]);
    throw $e; // Re-throw for application-level handling
} catch (RateLimitException $e) {
    // Handle rate limiting
    Log::warning('Rate limit hit', ['retry_after' => $e->getRetryAfter()]);
    sleep($e->getRetryAfter());
    // Retry logic here
} catch (ApiException $e) {
    // Handle API errors (404, 500, etc.)
    if ($e->getStatusCode() === 404) {
        Log::warning('Server not found', ['server_id' => $serverId]);
    } else {
        Log::error('API error', [
            'status' => $e->getStatusCode(),
            'response' => $e->getResponseData(),
        ]);
    }
} catch (ForgeException $e) {
    // Catch-all for any other SDK errors
    Log::error('Forge Client error', ['message' => $e->getMessage()]);
}
```

#### Laravel Exception Handler

Add to `app/Exceptions/Handler.php`:

```php
use ArtisanBuild\ForgeClient\Exceptions\RateLimitException;
use ArtisanBuild\ForgeClient\Exceptions\AuthenticationException;

public function register(): void
{
    $this->reportable(function (RateLimitException $e) {
        // Log rate limit hits for monitoring
        Log::warning('Forge rate limit exceeded', [
            'retry_after' => $e->getRetryAfter(),
        ]);
    });

    $this->reportable(function (AuthenticationException $e) {
        // Alert team about auth failures
        Log::critical('Forge authentication failed', [
            'message' => $e->getMessage(),
        ]);
    });
}
```

## Troubleshooting

### Common Issues

#### Authentication Failures

**Problem:** `AuthenticationException: Invalid API token`

**Solutions:**
1. Verify your API token in `.env`:
   ```env
   FORGE_API_TOKEN=your-actual-token
   ```
2. Generate a new token at https://forge.laravel.com/user-profile/api
3. Ensure the token has required permissions (full access recommended)
4. Check the token wasn't revoked in Forge dashboard

#### Rate Limiting

**Problem:** `RateLimitException: Too many requests`

**Details:** Forge API limits requests to **60 per minute** per API token.

**Solutions:**
1. Implement request throttling in your application:
   ```php
   use Illuminate\Support\Facades\Cache;

   $key = 'forge-api-requests';
   $requests = Cache::increment($key);

   if ($requests === 1) {
       Cache::put($key, 1, now()->addMinute());
   }

   if ($requests > 55) {
       sleep(60); // Wait for rate limit reset
   }
   ```

2. Batch operations when possible
3. Cache Forge data locally to reduce API calls
4. Use webhooks for real-time updates instead of polling

#### Server/Site Not Found

**Problem:** `ApiException: Server not found (404)`

**Solutions:**
1. Verify the server ID is correct
2. Check you're using the correct organization
3. Ensure the server wasn't deleted
4. If using server name, ensure it's unique:
   ```bash
   # This might fail if multiple servers named "staging"
   php artisan forge:get-server staging

   # Use ID instead
   php artisan forge:get-server 12345
   ```

#### Command Failures

**Problem:** Artisan commands hang or fail

**Solutions:**
1. Ensure defaults are configured if not passing arguments:
   ```env
   FORGE_ORGANIZATION=my-org
   FORGE_SERVER=my-server
   ```

2. Check command syntax:
   ```bash
   # Incorrect
   php artisan forge:create-server my-server

   # Correct
   php artisan forge:create-server \
     --name=my-server \
     --organization=my-org \
     --provider=ocean2 \
     --credential=123 \
     --size=456 \
     --region=nyc3
   ```

3. Use `--help` to see required options:
   ```bash
   php artisan forge:create-server --help
   ```

#### Logging Issues

**Problem:** No logs appearing for Forge operations

**Solutions:**
1. Check logging configuration:
   ```env
   FORGE_LOG_CHANNEL=stack
   FORGE_LOG_LEVEL=info
   ```

2. Ensure log channel exists in `config/logging.php`

3. Verify log permissions:
   ```bash
   chmod -R 775 storage/logs
   ```

#### SSL Certificate Errors

**Problem:** Let's Encrypt certificate creation fails

**Solutions:**
1. Verify domain DNS points to server
2. Ensure site is accessible via HTTP first
3. Check firewall allows ports 80 and 443
4. Domain must be publicly accessible (no local/private IPs)

#### Network/Timeout Issues

**Problem:** Requests timing out

**Solutions:**
1. Increase timeout in config:
   ```env
   FORGE_TIMEOUT=60
   ```

2. Check network connectivity:
   ```bash
   curl -I https://forge.laravel.com/api
   ```

3. Verify no firewall blocking outbound HTTPS

### Debug Mode

Enable detailed logging for troubleshooting:

```php
// In a service provider or bootstrap file
use Illuminate\Support\Facades\Log;

if (config('app.debug')) {
    // Log all Forge API calls
    Event::listen(\Saloon\Events\SendingRequest::class, function ($event) {
        Log::debug('Forge API Request', [
            'method' => $event->request->getMethod(),
            'uri' => $event->request->getUri(),
        ]);
    });

    Event::listen(\Saloon\Events\ResponseReceived::class, function ($event) {
        Log::debug('Forge API Response', [
            'status' => $event->response->status(),
            'body' => $event->response->body(),
        ]);
    });
}
```

### Getting Help

- **Forge API Documentation:** https://forge.laravel.com/docs/api-reference/introduction
- **Legacy API Documentation:** https://forge.laravel.com/api-documentation
- **Forge Status:** https://status.laravel.com/
- **Laravel Forge Community:** https://laracasts.com/discuss/channels/forge

## Contributing

This package is part of our internal toolkit and is optimized for our own purposes. We do not accept issues or PRs in this repository.

However, if you find this package useful and want to build upon it for your own needs, you're welcome to fork it!

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
