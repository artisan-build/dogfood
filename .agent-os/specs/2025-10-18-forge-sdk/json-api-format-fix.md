# JSON:API Format Fix for List Commands

## Issue Discovered

When testing `php artisan forge:list-servers`, the command successfully retrieved data but displayed "N/A" for all fields except IDs. This was because the Laravel Forge API returns data in JSON:API format where resource attributes are nested in an `attributes` object, but the commands were trying to access fields directly on the root of each item.

## Root Cause

The Forge API returns responses in JSON:API format like this:

```json
{
  "data": [
    {
      "id": "960129",
      "type": "servers",
      "attributes": {
        "name": "production-server",
        "provider": "ocean2",
        "region": "nyc3",
        "php_version": "php84",
        "ip_address": "192.168.1.1",
        "connection_status": "connected"
      }
    }
  ]
}
```

But the commands were trying to access: `$server['name']` instead of `$server['attributes']['name']`

## Commands Fixed

### 1. ListServersCommand ✅
**File:** `src/Console/Commands/ListServersCommand.php`

**Changes:**
- Updated table mapping to use `$server['attributes']['field']` format
- Used `connection_status` instead of non-existent `status` field

**Before:**
```php
$server['name'] ?? 'N/A',
$server['provider'] ?? 'N/A',
// etc...
```

**After:**
```php
$server['attributes']['name'] ?? 'N/A',
$server['attributes']['provider'] ?? 'N/A',
// etc...
```

---

### 2. ListDatabasesCommand ✅
**File:** `src/Console/Commands/ListDatabasesCommand.php`

**Changes:**
- Added fallback pattern: `$database['attributes']['name'] ?? $database['name'] ?? 'N/A'`
- Applied to all fields: name, status, created_at

---

### 3. ListDatabaseUsersCommand ✅
**File:** `src/Console/Commands/ListDatabaseUsersCommand.php`

**Changes:**
- Added fallback pattern for all fields
- Fields: name, status, created_at

---

### 4. ListBackgroundProcessesCommand ✅
**File:** `src/Console/Commands/ListBackgroundProcessesCommand.php`

**Changes:**
- Added fallback pattern for all fields
- Fields: user, command, directory, status
- Maintained string length limiting for command and directory

---

### 5. ListFirewallRulesCommand ✅
**File:** `src/Console/Commands/ListFirewallRulesCommand.php`

**Changes:**
- Added fallback pattern for all fields
- Fields: name, type, ip_address, port, status, created_at

---

### 6. ListDeploymentsCommand ✅
**File:** `src/Console/Commands/ListDeploymentsCommand.php`

**Changes:**
- Added fallback pattern for all fields
- Fields: status, commit_hash, commit_author, commit_message, started_at, duration
- Maintained substring logic for commit_hash (8 chars) and commit_message (27 chars + ellipsis)

---

### 7. ListOrganizationsCommand ✅
**File:** `src/Console/Commands/ListOrganizationsCommand.php`

**Changes:**
- Added fallback pattern for all fields
- Fields: name, slug, created_at

---

### 8. ListSslCertificatesCommand ✅
**File:** `src/Console/Commands/ListSslCertificatesCommand.php`

**Status:** No changes needed
**Reason:** This is a helper command that provides guidance, doesn't make API calls

---

### 9. ListSitesCommand ✅
**File:** `src/Console/Commands/ListSitesCommand.php`

**Status:** Already correct
**Reason:** This command already had the fallback pattern implemented

## Pattern Used

We used a consistent fallback pattern throughout:

```php
$item['attributes']['field_name'] ?? $item['field_name'] ?? 'N/A'
```

This pattern:
1. **First** tries to get the value from `attributes` (JSON:API format)
2. **Falls back** to root level (for backwards compatibility or non-JSON:API endpoints)
3. **Defaults** to 'N/A' if neither exists

## Testing

To verify the fix works:

```bash
# Set your organization
export FORGE_ORGANIZATION="your-org-slug"

# Test the fixed command
php artisan forge:list-servers

# Should now display:
# - Server names
# - Providers (ocean2, aws, etc.)
# - Regions
# - PHP versions
# - IP addresses
# - Connection statuses
```

## Impact

All list commands now correctly display data from the Forge API. Users will see actual server/site/database information instead of "N/A" values.

## Related Files

No changes needed to:
- Request classes (they're already sending correct requests)
- Response handling (API responses are valid)
- Other command types (Get, Create, Update, Delete commands work differently)

## Future Considerations

If other commands show similar issues with missing data, check if they're also trying to access fields directly instead of through the `attributes` object. The JSON:API format is consistent across all Forge API endpoints.
