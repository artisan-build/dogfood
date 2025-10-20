# README.md Updates Summary

## Date
2025-10-19

## Overview
Updated the Laravel Forge SDK README.md to reflect the actual API behavior based on the official API documentation downloaded as markdown files from https://forge.laravel.com/docs/api-reference/.

## Changes Made

### 1. Cloud Provider Enum ✅

**Before:**
```php
CloudProvider::DIGITAL_OCEAN; // 'digitalocean'
CloudProvider::LINODE;         // 'linode'
CloudProvider::VULTR;          // 'vultr'
CloudProvider::AWS;            // 'aws'
CloudProvider::HETZNER;        // 'hetzner'
CloudProvider::CUSTOM;         // 'custom'
```

**After:**
```php
CloudProvider::AWS;      // 'aws'
CloudProvider::OCEAN2;   // 'ocean2' (DigitalOcean)
CloudProvider::HETZNER;  // 'hetzner'
CloudProvider::VULTR;    // 'vultr'
CloudProvider::AKAMAI;   // 'akamai' (Linode/Akamai)
CloudProvider::LARAVEL;  // 'laravel' (Laravel Cloud)
CloudProvider::CUSTOM;   // 'custom'
```

**Changes:**
- Changed `DIGITAL_OCEAN` → `OCEAN2` (API uses 'ocean2', not 'digitalocean')
- Changed `LINODE` → `AKAMAI` (Linode was acquired by Akamai)
- Added `LARAVEL` for Laravel Cloud
- Updated all examples throughout README to use `ocean2` instead of `digitalocean`

---

### 2. Site Type Enum ✅ (Major Rewrite)

**Before:**
```php
SiteType::PHP;          // 'php'
SiteType::HTML;         // 'html'
SiteType::SYMFONY;      // 'symfony'
SiteType::SYMFONY_DEV;  // 'symfony_dev'
SiteType::SYMFONY_FOUR; // 'symfony_four'
SiteType::SYMFONY_FIVE; // 'symfony_five'
```

**After:**
```php
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

**Changes:**
- Completely rewrote the enum to match actual API (11 types instead of 6)
- Removed incorrect types: `HTML`, `SYMFONY_DEV`, `SYMFONY_FOUR`, `SYMFONY_FIVE`
- Added modern frameworks: `LARAVEL`, `STATAMIC`, `WORDPRESS`, `PHPMYADMIN`, `NEXTJS`, `NUXTJS`, `STATIC_HTML`, `OTHER`, `CUSTOM`

---

### 3. Server Type Enum ✅

**Before:**
```php
ServerType::APP;           // 'app'
ServerType::WEB;           // 'web'
ServerType::DATABASE;      // 'database'
ServerType::CACHE;         // 'cache'
ServerType::LOAD_BALANCER; // 'load-balancer'
```

**After:**
```php
ServerType::APP;          // 'app'
ServerType::WEB;          // 'web'
ServerType::LOADBALANCER; // 'loadbalancer'
ServerType::DATABASE;     // 'database'
ServerType::CACHE;        // 'cache'
ServerType::WORKER;       // 'worker'
ServerType::MEILISEARCH;  // 'meilisearch'
```

**Changes:**
- Fixed `LOAD_BALANCER` → `LOADBALANCER` (API uses no hyphen)
- Added `WORKER` and `MEILISEARCH` server types

---

### 4. PHP Version Enum ✅

**Before:**
```php
PhpVersion::PHP74; // 'php74'
PhpVersion::PHP80; // 'php80'
PhpVersion::PHP81; // 'php81'
PhpVersion::PHP82; // 'php82'
PhpVersion::PHP83; // 'php83'
PhpVersion::PHP84; // 'php84'
```

**After:**
```php
PhpVersion::PHP74; // 'php74'
PhpVersion::PHP80; // 'php80'
PhpVersion::PHP81; // 'php81'
PhpVersion::PHP82; // 'php82'
PhpVersion::PHP83; // 'php83'
PhpVersion::PHP84; // 'php84'
PhpVersion::PHP85; // 'php85'
```

**Changes:**
- Added `PHP85` for PHP 8.5 support
- Updated `latest()` helper method comment to reflect PHP 8.5

---

### 5. API Documentation URL ✅

**Before:**
```markdown
- **Forge API Documentation:** https://forge.laravel.com/api-documentation
```

**After:**
```markdown
- **Forge API Documentation:** https://forge.laravel.com/docs/api-reference/introduction
- **Legacy API Documentation:** https://forge.laravel.com/api-documentation
```

**Changes:**
- Updated primary URL to new API reference documentation
- Kept legacy URL as secondary reference

---

### 6. Command Examples ✅

Updated all command-line examples throughout the README:

**Example changes:**
```bash
# Before
php artisan forge:create-server --provider=digitalocean

# After
php artisan forge:create-server --provider=ocean2
```

**Affected sections:**
- Quick Start examples (line ~168)
- Server listing examples (line ~258)
- Server creation examples (line ~306, ~1276)
- Filter examples (line ~1230)

---

### 7. Command Options Documentation ✅

Updated the documented options for commands:

**Before:**
```
--provider= - Cloud provider: digitalocean, linode, vultr, aws, hetzner, custom
```

**After:**
```
--provider= - Cloud provider: aws, ocean2, hetzner, vultr, akamai, laravel, custom
```

**Before:**
```
--project-type= - Project type: php, html, symfony, symfony_dev, symfony_four, symfony_five
```

**After:**
```
--project-type= - Project type: laravel, symfony, statamic, wordpress, phpmyadmin, php, next.js, nuxt.js, static-html, other, custom
```

---

### 8. Code Examples ✅

Updated PHP code examples using enums:

**Before:**
```php
$provider = CloudProvider::DIGITAL_OCEAN;
if (CloudProvider::isValid('digitalocean')) { }
```

**After:**
```php
$provider = CloudProvider::OCEAN2;
if (CloudProvider::isValid('ocean2')) { }
```

---

## Files Modified

1. `/packages/forge-sdk/README.md` - All changes above

## Verification

All changes were verified against the actual Laravel Forge API documentation:
- Source: `api-docs/servers-create-server.md` (for cloud providers and server types)
- Source: `api-docs/sites-create-site.md` (for site types)
- Total pages fetched: 238 markdown files

## What Was NOT Changed

The following were verified as correct and left unchanged:
- ✅ Ubuntu versions (2204, 2404)
- ✅ Database types (mysql8, mysql, mariadb, postgres, none)
- ✅ Firewall rule types
- ✅ Certificate types
- ✅ Integration types
- ✅ Other enums

## Impact

These changes ensure the README accurately reflects:
1. Current Laravel Forge API provider names (especially DigitalOcean/ocean2)
2. Complete list of supported site types (Laravel, Statamic, WordPress, etc.)
3. All available server types (including worker and meilisearch)
4. Future-proof PHP version support (PHP 8.5)
5. Correct API documentation URL for the new API

## Next Steps

The actual enum classes in the codebase will need to be updated to match these changes:
- `src/Enums/CloudProvider.php`
- `src/Enums/ServerType.php`
- `src/Enums/SiteType.php`
- `src/Enums/PhpVersion.php`

These enum updates should be handled in a separate task to update the actual SDK code.
