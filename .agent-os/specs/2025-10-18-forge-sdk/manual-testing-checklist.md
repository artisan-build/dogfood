# Manual Testing Checklist for Forge SDK Commands

> **Important:** Run these test suites independently. Each suite is designed to leave your Forge account in the same state as before you started.

## Prerequisites

Before running any test suite:

1. Set environment variables:
```bash
export FORGE_API_TOKEN="your-token-here"
export FORGE_ORGANIZATION="your-org-slug"
```

2. Have a test server ready (or create one in Test Suite 1)
3. Note your current Forge account state (number of servers, sites, etc.)

---

## Test Suite 1: Server Lifecycle (Complete CRUD)

**Purpose:** Test server creation, retrieval, updates, and deletion
**Duration:** ~10-15 minutes (server provisioning time)
**Prerequisite:** Need a valid credential for your cloud provider

### Setup
- [x] Note current server count: `php artisan forge:list-servers --organization=$FORGE_ORGANIZATION`

### Test Steps

#### Provider Information
1. [x] **List server credentials**
   ```bash
   php artisan forge:list-server-credentials --organization=$FORGE_ORGANIZATION
   ```
   - Note the credential ID you want to use: `________________`
   - This credential contains your cloud provider API keys

2. [x] **List providers and choose one**
   ```bash
   php artisan forge:list-providers
   ```
   - Note the provider ID you want to use: `________________`
   - Laravel provider (ID 1) provisions fastest

3. [x] **Check available regions for provider**
   ```bash
   php artisan forge:list-provider-regions [PROVIDER_ID]
   ```
   - Verify regions are listed with name, code, and alternate code
   - Note the region code you want to use: `________________`

4. [x] **Check available server sizes for provider**
   ```bash
   php artisan forge:list-provider-sizes [PROVIDER_ID]
   ```
   - Verify sizes are listed with RAM, CPUs, disk, and architecture
   - Note the size ID you want to use: `________________`

#### Create Operations
5. [x] **Create a test server**
   ```bash
   # Using Laravel Forge provider (fastest provisioning)
   php artisan forge:create-server \
     --organization=$FORGE_ORGANIZATION \
     --name=sdk-test-server \
     --provider=laravel \
     --credential=[CREDENTIAL_ID] \
     --region=[REGION_CODE_FROM_STEP_3] \
     --size=[SIZE_ID_FROM_STEP_4] \
     --php-version=php84 \
     --database=mysql8

   # OR using DigitalOcean (slower provisioning)
   # php artisan forge:create-server \
   #   --organization=$FORGE_ORGANIZATION \
   #   --name=sdk-test-server \
   #   --provider=ocean2 \
   #   --credential=[CREDENTIAL_ID] \
   #   --region=[REGION_CODE_FROM_STEP_3] \
   #   --size=[SIZE_ID_FROM_STEP_4] \
   #   --php-version=php84 \
   #   --database=mysql8
   ```
   
    My shortcut: `art forge:create-server --name=sdk-test-server --provider=laravel --credential=302805 --size=1 --region=nyc1 --database=mysql8`
   - Note the server ID: `________________`
   - Note the server ID: `________________`

6. [x] **Wait for server to provision** (check status)
   ```bash
   php artisan forge:get-server [SERVER_ID] --organization=$FORGE_ORGANIZATION
   ```
   - Repeat until status shows "installed"

#### Read Operations
5. [x] **List all servers**
   ```bash
   php artisan forge:list-servers --organization=$FORGE_ORGANIZATION
   ```
   - Verify sdk-test-server appears in list

6. [x] **Get server by ID**
   ```bash
   php artisan forge:get-server [SERVER_ID] --organization=$FORGE_ORGANIZATION
   ```
   - Verify details are correct

7. [x] **Get server by name**
   ```bash
   php artisan forge:get-server sdk-test-server $FORGE_ORGANIZATION
   ```
   - Should return same server

8. [x] **List servers with filters**
   ```bash
   php artisan forge:list-servers \
     --organization=$FORGE_ORGANIZATION \
     --filter-provider=laravel
   ```
   - Verify filtering works

#### Cleanup (Restore State)
9. [ ] **Delete the test server**
   ```bash
   php artisan forge:destroy-server [SERVER_ID] \
     --organization=$FORGE_ORGANIZATION \
     --dangerously-skip-confirmation
   ```

10. [ ] **Verify server is deleted**
   ```bash
   php artisan forge:list-servers --organization=$FORGE_ORGANIZATION
   ```
   - Verify count matches original

### Result
- [ ] ✅ All tests passed
- [ ] ❌ Issues found: _______________________

---

## Test Suite 2: Site Lifecycle (Requires Existing Server)

**Purpose:** Test site creation, deployment, SSL, and deletion
**Duration:** ~5-10 minutes
**Prerequisite:** Have an existing test server with ID: `________________`

### Setup
- [ ] Note current site count on server:
   ```bash
   php artisan forge:list-sites \
     --organization=$FORGE_ORGANIZATION \
     --server=[SERVER_ID]
   ```

### Test Steps

#### Create Site
1. [ ] **Create a test site**
   ```bash
   php artisan forge:create-site \
     --organization=$FORGE_ORGANIZATION \
     --server=[SERVER_ID] \
     --domain=sdk-test-$(date +%s).example.com \
     --project-type=php \
     --directory=/public
   ```
   - Note the site ID: `________________`
   - Note the domain: `________________`

2. [ ] **Wait for site creation** (check status)
   ```bash
   php artisan forge:get-site [SITE_ID] \
     --organization=$FORGE_ORGANIZATION \
     --server=[SERVER_ID]
   ```

#### Read Operations
3. [ ] **List all sites on server**
   ```bash
   php artisan forge:list-sites \
     --organization=$FORGE_ORGANIZATION \
     --server=[SERVER_ID]
   ```
   - Verify test site appears

4. [ ] **Get site by ID**
   ```bash
   php artisan forge:get-site [SITE_ID] \
     --organization=$FORGE_ORGANIZATION \
     --server=[SERVER_ID]
   ```

5. [ ] **Get site by domain**
   ```bash
   php artisan forge:get-site [DOMAIN] \
     --organization=$FORGE_ORGANIZATION \
     --server=[SERVER_ID]
   ```

#### Update Operations
6. [ ] **Update site directory**
   ```bash
   php artisan forge:update-site [SITE_ID] \
     --organization=$FORGE_ORGANIZATION \
     --server=[SERVER_ID] \
     --directory=/
   ```

#### Deployment Operations
7. [ ] **Enable quick deploy**
   ```bash
   php artisan forge:enable-quick-deploy [SITE_ID] \
     --organization=$FORGE_ORGANIZATION \
     --server=[SERVER_ID]
   ```

8. [ ] **Disable quick deploy**
   ```bash
   php artisan forge:disable-quick-deploy [SITE_ID] \
     --organization=$FORGE_ORGANIZATION \
     --server=[SERVER_ID]
   ```

9. [ ] **List deployments**
   ```bash
   php artisan forge:list-deployments [SITE_ID] \
     --organization=$FORGE_ORGANIZATION \
     --server=[SERVER_ID]
   ```

#### Cleanup (Restore State)
10. [ ] **Delete the test site**
    ```bash
    php artisan forge:destroy-site [SITE_ID] \
      --organization=$FORGE_ORGANIZATION \
      --server=[SERVER_ID] \
      --dangerously-skip-confirmation
    ```

11. [ ] **Verify site is deleted**
    ```bash
    php artisan forge:list-sites \
      --organization=$FORGE_ORGANIZATION \
      --server=[SERVER_ID]
    ```
    - Verify count matches original

### Result
- [ ] ✅ All tests passed
- [ ] ❌ Issues found: _______________________

---

## Test Suite 3: Database Operations (Requires Existing Server)

**Purpose:** Test database and database user CRUD operations
**Duration:** ~3-5 minutes
**Prerequisite:** Have an existing test server with ID: `________________`

### Setup
- [ ] Note current database count:
   ```bash
   php artisan forge:list-databases \
     --organization=$FORGE_ORGANIZATION \
     --server=[SERVER_ID]
   ```
- [ ] Note current database user count:
   ```bash
   php artisan forge:list-database-users \
     --organization=$FORGE_ORGANIZATION \
     --server=[SERVER_ID]
   ```

### Test Steps

#### Database Operations
1. [ ] **Create a test database**
   ```bash
   php artisan forge:create-database sdk_test_db [SERVER_ID] $FORGE_ORGANIZATION
   ```
   - Note database ID: `________________`

2. [ ] **List databases**
   ```bash
   php artisan forge:list-databases \
     --organization=$FORGE_ORGANIZATION \
     --server=[SERVER_ID]
   ```
   - Verify sdk_test_db appears

3. [ ] **Get database details**
   ```bash
   php artisan forge:get-database [DB_ID] \
     --organization=$FORGE_ORGANIZATION \
     --server=[SERVER_ID]
   ```

4. [ ] **Get database by name**
   ```bash
   php artisan forge:get-database sdk_test_db \
     --organization=$FORGE_ORGANIZATION \
     --server=[SERVER_ID]
   ```

#### Database User Operations
5. [ ] **Create a test database user**
   ```bash
   php artisan forge:create-database-user \
     --organization=$FORGE_ORGANIZATION \
     --server=[SERVER_ID] \
     --name=sdk_test_user \
     --password=TestPassword123! \
     --databases=sdk_test_db
   ```
   - Note user ID: `________________`

6. [ ] **List database users**
   ```bash
   php artisan forge:list-database-users \
     --organization=$FORGE_ORGANIZATION \
     --server=[SERVER_ID]
   ```
   - Verify sdk_test_user appears

7. [ ] **Get database user details**
   ```bash
   php artisan forge:get-database-user [USER_ID] \
     --organization=$FORGE_ORGANIZATION \
     --server=[SERVER_ID]
   ```

8. [ ] **Update database user permissions**
   ```bash
   php artisan forge:update-database-user [USER_ID] \
     --organization=$FORGE_ORGANIZATION \
     --server=[SERVER_ID] \
     --databases=sdk_test_db
   ```

#### Cleanup (Restore State)
9. [ ] **Delete database user**
   ```bash
   php artisan forge:destroy-database-user [USER_ID] \
     --organization=$FORGE_ORGANIZATION \
     --server=[SERVER_ID] \
     --dangerously-skip-confirmation
   ```

10. [ ] **Delete database**
    ```bash
    php artisan forge:destroy-database [DB_ID] \
      --organization=$FORGE_ORGANIZATION \
      --server=[SERVER_ID] \
      --dangerously-skip-confirmation
    ```

11. [ ] **Verify cleanup**
    ```bash
    php artisan forge:list-databases \
      --organization=$FORGE_ORGANIZATION \
      --server=[SERVER_ID]

    php artisan forge:list-database-users \
      --organization=$FORGE_ORGANIZATION \
      --server=[SERVER_ID]
    ```
    - Verify counts match original

### Result
- [ ] ✅ All tests passed
- [ ] ❌ Issues found: _______________________

---

## Test Suite 4: Background Processes (Requires Existing Server)

**Purpose:** Test daemon/background process management
**Duration:** ~3-5 minutes
**Prerequisite:** Have an existing test server with ID: `________________`

### Setup
- [ ] Note current background process count:
   ```bash
   php artisan forge:list-background-processes \
     --organization=$FORGE_ORGANIZATION \
     --server=[SERVER_ID]
   ```

### Test Steps

1. [ ] **Create a test background process**
   ```bash
   php artisan forge:create-background-process \
     --organization=$FORGE_ORGANIZATION \
     --server=[SERVER_ID] \
     --command="php /home/forge/test.php" \
     --user=forge \
     --directory=/home/forge
   ```
   - Note process ID: `________________`

2. [ ] **List background processes**
   ```bash
   php artisan forge:list-background-processes \
     --organization=$FORGE_ORGANIZATION \
     --server=[SERVER_ID]
   ```
   - Verify test process appears

3. [ ] **Get background process details**
   ```bash
   php artisan forge:get-background-process [PROCESS_ID] \
     --organization=$FORGE_ORGANIZATION \
     --server=[SERVER_ID]
   ```

4. [ ] **Update background process**
   ```bash
   php artisan forge:update-background-process [PROCESS_ID] \
     --organization=$FORGE_ORGANIZATION \
     --server=[SERVER_ID] \
     --command="php /home/forge/updated-test.php"
   ```

5. [ ] **Restart background process**
   ```bash
   php artisan forge:restart-background-process [PROCESS_ID] \
     --organization=$FORGE_ORGANIZATION \
     --server=[SERVER_ID]
   ```

#### Cleanup (Restore State)
6. [ ] **Delete background process**
   ```bash
   php artisan forge:destroy-background-process [PROCESS_ID] \
     --organization=$FORGE_ORGANIZATION \
     --server=[SERVER_ID] \
     --dangerously-skip-confirmation
   ```

7. [ ] **Verify cleanup**
   ```bash
   php artisan forge:list-background-processes \
     --organization=$FORGE_ORGANIZATION \
     --server=[SERVER_ID]
   ```
   - Verify count matches original

### Result
- [ ] ✅ All tests passed
- [ ] ❌ Issues found: _______________________

---

## Test Suite 5: Firewall Rules (Requires Existing Server)

**Purpose:** Test firewall rule creation and deletion
**Duration:** ~2-3 minutes
**Prerequisite:** Have an existing test server with ID: `________________`

### Setup
- [ ] Note current firewall rule count:
   ```bash
   php artisan forge:list-firewall-rules \
     --organization=$FORGE_ORGANIZATION \
     --server=[SERVER_ID]
   ```

### Test Steps

1. [ ] **Create an allow rule**
   ```bash
   php artisan forge:create-firewall-rule \
     --organization=$FORGE_ORGANIZATION \
     --server=[SERVER_ID] \
     --name="SDK Test Allow Redis" \
     --port=6379 \
     --type=allow \
     --ip-address=192.168.1.100
   ```
   - Note rule ID: `________________`

2. [ ] **List firewall rules**
   ```bash
   php artisan forge:list-firewall-rules \
     --organization=$FORGE_ORGANIZATION \
     --server=[SERVER_ID]
   ```
   - Verify test rule appears

3. [ ] **Get firewall rule details**
   ```bash
   php artisan forge:get-firewall-rule [RULE_ID] \
     --organization=$FORGE_ORGANIZATION \
     --server=[SERVER_ID]
   ```

4. [ ] **Create a deny rule**
   ```bash
   php artisan forge:create-firewall-rule \
     --organization=$FORGE_ORGANIZATION \
     --server=[SERVER_ID] \
     --name="SDK Test Deny" \
     --port=9999 \
     --type=deny
   ```
   - Note rule ID: `________________`

#### Cleanup (Restore State)
5. [ ] **Delete allow rule**
   ```bash
   php artisan forge:destroy-firewall-rule [ALLOW_RULE_ID] \
     --organization=$FORGE_ORGANIZATION \
     --server=[SERVER_ID] \
     --dangerously-skip-confirmation
   ```

6. [ ] **Delete deny rule**
   ```bash
   php artisan forge:destroy-firewall-rule [DENY_RULE_ID] \
     --organization=$FORGE_ORGANIZATION \
     --server=[SERVER_ID] \
     --dangerously-skip-confirmation
   ```

7. [ ] **Verify cleanup**
   ```bash
   php artisan forge:list-firewall-rules \
     --organization=$FORGE_ORGANIZATION \
     --server=[SERVER_ID]
   ```
   - Verify count matches original

### Result
- [ ] ✅ All tests passed
- [ ] ❌ Issues found: _______________________

---

## Test Suite 6: Organization Operations (Read-Only)

**Purpose:** Test organization listing and retrieval
**Duration:** ~1 minute
**Prerequisite:** None (read-only operations)

### Test Steps

1. [ ] **List all organizations**
   ```bash
   php artisan forge:list-organizations
   ```
   - Verify your organizations appear

2. [ ] **Get organization by slug**
   ```bash
   php artisan forge:get-organization $FORGE_ORGANIZATION
   ```
   - Verify details are correct

3. [ ] **Get organization by ID** (if you know the ID)
   ```bash
   php artisan forge:get-organization [ORG_ID]
   ```

### Result
- [ ] ✅ All tests passed
- [ ] ❌ Issues found: _______________________

---

## Test Suite 7: SSL Certificates (Requires Existing Server & Site)

**Purpose:** Test SSL certificate operations (Note: Let's Encrypt won't work with test domains)
**Duration:** ~3-5 minutes
**Prerequisite:**
- Have an existing test server with ID: `________________`
- Have an existing test site with ID: `________________`

### Setup
- [ ] Note current certificate count:
   ```bash
   php artisan forge:list-ssl-certificates [SITE_ID] \
     --organization=$FORGE_ORGANIZATION \
     --server=[SERVER_ID]
   ```

### Test Steps

1. [ ] **List SSL certificates**
   ```bash
   php artisan forge:list-ssl-certificates [SITE_ID] \
     --organization=$FORGE_ORGANIZATION \
     --server=[SERVER_ID]
   ```

2. [ ] **Get certificate details** (if any exist)
   ```bash
   php artisan forge:get-ssl-certificate [CERT_ID] \
     --site=[SITE_ID] \
     --organization=$FORGE_ORGANIZATION \
     --server=[SERVER_ID]
   ```

> **Note:** Creating test SSL certificates requires either:
> - A real domain you control (for Let's Encrypt)
> - Self-signed certificate files (for existing certificate type)
>
> Skip certificate creation/deletion unless you have a test domain ready.

### Result
- [ ] ✅ All tests passed
- [ ] ❌ Issues found: _______________________
- [ ] ⚠️  Skipped (no test domain available)

---

## Complete Integration Test (All Resources)

**Purpose:** Test complete lifecycle with all resources together
**Duration:** ~15-20 minutes
**Prerequisite:** Valid cloud provider credential

This test creates a complete stack and then tears it all down.

### Phase 1: Build Up

1. [ ] **List server credentials**
   ```bash
   php artisan forge:list-server-credentials --organization=$FORGE_ORGANIZATION
   ```
   - Note the credential ID: `________________`

2. [ ] **Choose a provider**
   ```bash
   php artisan forge:list-providers
   ```
   - Note the provider ID: `________________`
   - Laravel provider (ID 1) provisions fastest

3. [ ] **Get provider region**
   ```bash
   php artisan forge:list-provider-regions [PROVIDER_ID]
   ```
   - Note the region code: `________________`

4. [ ] **Check available server sizes**
   ```bash
   php artisan forge:list-provider-sizes [PROVIDER_ID]
   ```
   - Choose an appropriate size for testing
   - Note the size ID: `________________`

5. [ ] **Create server**
   ```bash
   # Using Laravel Forge provider (recommended for testing - fast provisioning)
   php artisan forge:create-server \
     --organization=$FORGE_ORGANIZATION \
     --name=sdk-integration-test \
     --provider=laravel \
     --credential=[CREDENTIAL_ID] \
     --region=[REGION_CODE_FROM_STEP_3] \
     --size=[SIZE_ID_FROM_STEP_4] \
     --php-version=php84 \
     --database=mysql8

   # OR using DigitalOcean (slower provisioning)
   # php artisan forge:create-server \
   #   --organization=$FORGE_ORGANIZATION \
   #   --name=sdk-integration-test \
   #   --provider=ocean2 \
   #   --credential=[CREDENTIAL_ID] \
   #   --region=[REGION_CODE_FROM_STEP_3] \
   #   --size=[SIZE_ID_FROM_STEP_4] \
   #   --php-version=php84 \
   #   --database=mysql8
   ```
   - Server ID: `________________`

4. [ ] **Wait for server provisioning** (check with get-server until ready)

5. [ ] **Create database**
   ```bash
   php artisan forge:create-database \
     --organization=$FORGE_ORGANIZATION \
     --server=[SERVER_ID] \
     --name=integration_test_db
   ```
   - Database ID: `________________`

6. [ ] **Create database user**
   ```bash
   php artisan forge:create-database-user \
     --organization=$FORGE_ORGANIZATION \
     --server=[SERVER_ID] \
     --name=integration_user \
     --password=TestPass123! \
     --databases=integration_test_db
   ```
   - User ID: `________________`

7. [ ] **Create site**
   ```bash
   php artisan forge:create-site \
     --organization=$FORGE_ORGANIZATION \
     --server=[SERVER_ID] \
     --domain=integration-test-$(date +%s).example.com \
     --project-type=laravel \
     --directory=/public
   ```
   - Site ID: `________________`

8. [ ] **Create background process**
   ```bash
   php artisan forge:create-background-process \
     --organization=$FORGE_ORGANIZATION \
     --server=[SERVER_ID] \
     --command="php artisan queue:work" \
     --user=forge \
     --directory=/home/forge/[DOMAIN]
   ```
   - Process ID: `________________`

9. [ ] **Create firewall rule**
   ```bash
   php artisan forge:create-firewall-rule \
     --organization=$FORGE_ORGANIZATION \
     --server=[SERVER_ID] \
     --name="Integration Test Rule" \
     --port=6379 \
     --type=allow \
     --ip-address=10.0.0.0/8
   ```
   - Rule ID: `________________`

### Phase 2: Verify

10. [ ] **Verify all resources exist**
    ```bash
    php artisan forge:get-server [SERVER_ID] --organization=$FORGE_ORGANIZATION
    php artisan forge:get-database [DB_ID] --organization=$FORGE_ORGANIZATION --server=[SERVER_ID]
    php artisan forge:get-database-user [USER_ID] --organization=$FORGE_ORGANIZATION --server=[SERVER_ID]
    php artisan forge:get-site [SITE_ID] --organization=$FORGE_ORGANIZATION --server=[SERVER_ID]
    php artisan forge:get-background-process [PROCESS_ID] --organization=$FORGE_ORGANIZATION --server=[SERVER_ID]
    php artisan forge:get-firewall-rule [RULE_ID] --organization=$FORGE_ORGANIZATION --server=[SERVER_ID]
    ```

### Phase 3: Tear Down (Reverse Order)

11. [ ] **Delete firewall rule**
    ```bash
    php artisan forge:destroy-firewall-rule [RULE_ID] \
      --organization=$FORGE_ORGANIZATION \
      --server=[SERVER_ID] \
      --dangerously-skip-confirmation
    ```

12. [ ] **Delete background process**
    ```bash
    php artisan forge:destroy-background-process [PROCESS_ID] \
      --organization=$FORGE_ORGANIZATION \
      --server=[SERVER_ID] \
      --dangerously-skip-confirmation
    ```

13. [ ] **Delete site**
    ```bash
    php artisan forge:destroy-site [SITE_ID] \
      --organization=$FORGE_ORGANIZATION \
      --server=[SERVER_ID] \
      --dangerously-skip-confirmation
    ```

14. [ ] **Delete database user**
    ```bash
    php artisan forge:destroy-database-user [USER_ID] \
      --organization=$FORGE_ORGANIZATION \
      --server=[SERVER_ID] \
      --dangerously-skip-confirmation
    ```

15. [ ] **Delete database**
    ```bash
    php artisan forge:destroy-database [DB_ID] \
      --organization=$FORGE_ORGANIZATION \
      --server=[SERVER_ID] \
      --dangerously-skip-confirmation
    ```

16. [ ] **Delete server**
    ```bash
    php artisan forge:destroy-server [SERVER_ID] \
      --organization=$FORGE_ORGANIZATION \
      --dangerously-skip-confirmation
    ```

17. [ ] **Verify complete cleanup**
    ```bash
    php artisan forge:list-servers --organization=$FORGE_ORGANIZATION
    ```
    - Verify integration test server is gone

### Result
- [ ] ✅ All tests passed - account returned to original state
- [ ] ❌ Issues found: _______________________

---

## Testing Notes Template

Use this section to document any issues found during testing:

### Issue Log

#### Issue 1
- **Command:**
- **Expected:**
- **Actual:**
- **Error Message:**

#### Issue 2
- **Command:**
- **Expected:**
- **Actual:**
- **Error Message:**

---

## Summary

After completing all test suites, fill this out:

- **Test Suites Completed:** ___ / 8
- **Total Commands Tested:** ___
- **Commands Passed:** ___
- **Commands Failed:** ___
- **Account State Restored:** ✅ / ❌
- **Overall Assessment:**

**Signature:** _________________ **Date:** _________________
