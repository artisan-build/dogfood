# Tests Specification

This is the tests coverage details for the spec detailed in @.agent-os/specs/2025-10-20-rename-forge-sdk/spec.md

> Created: 2025-10-20
> Version: 1.0.0

## Test Coverage

### Verification Tests

**Post-Rename Verification**
- All existing tests in the package pass after namespace changes
- No test logic changes required - only namespace updates in test files
- Test file namespaces updated from `ArtisanBuild\ForgeSdk\Tests\` to `ArtisanBuild\ForgeClient\Tests\`

### Unit Tests

**Service Provider Tests**
- Service provider registers correctly with new class name
- Config publishes with new `forge-client` key
- Facade accessor returns correct binding

**Enum Tests** (existing tests, namespace updated)
- All enum tests in `tests/Unit/Enums/` continue to pass
- CloudProviderTest, DatabaseTypeTest, PhpVersionTest, etc.

**Exception Tests** (existing tests, namespace updated)
- ApiExceptionTest
- AuthenticationExceptionTest
- ForgeExceptionTest
- RateLimitExceptionTest
- ValidationExceptionTest

### Feature Tests

**Resource Tests** (existing tests, namespace updated)
- OrganizationsTest
- ServersTest
- SitesTest
- DatabasesTest
- FirewallRulesTest
- BackgroundProcessesTest
- DeploymentsTest
- ScheduledJobsTest
- RecipesTest

**Command Tests** (existing tests, namespace updated)
- OrganizationCommandsTest
- ServerCommandsTest
- SiteCommandsTest
- DatabaseCommandsTest
- FirewallRuleCommandsTest
- SslCertificateCommandsTest
- DeploymentCommandsTest
- BackgroundProcessCommandsTest
- ProviderCommandsTest
- ServerCredentialCommandsTest
- ResourceIdentifierResolutionTest

**Service Provider Test**
- ForgeSdkServiceProviderTest → ForgeClientServiceProviderTest

### Integration Tests

**Monorepo Integration**
- Package autoloads correctly from root composer.json
- `composer install` completes successfully
- `composer dump-autoload` regenerates autoloader correctly

**Config Integration**
- Config file publishes to `config/forge-client.php`
- Config values accessible via `config('forge-client.*)`
- Old `forge-sdk` config key no longer used

### Code Quality Tests

**Pint/Code Style**
- All files pass Laravel Pint formatting rules
- Namespace declarations properly formatted

**PHPStan**
- Static analysis passes at level 5
- No undefined class or namespace errors
- All type hints resolve correctly

**Rector**
- Code modernization rules apply cleanly
- No deprecated patterns introduced

### Full Suite Verification

**Monorepo Test Suite**
- Run `composer test` from root - all tests pass
- Run `composer test-parallel` - all tests pass in parallel mode
- Run `composer ready` - complete quality check passes

**Package Isolation Test**
- Tests run correctly in package-only context (for future CI after split)

## Mocking Requirements

No new mocking requirements. All existing mock strategies remain unchanged:
- Saloon connector mocking for API tests
- Forge API response mocking via MockResponseFactory

## Test Execution Order

1. Update all test file namespaces
2. Run package-specific tests: `composer test -- --filter="ForgeClient"`
3. Run full monorepo test suite: `composer test-parallel`
4. Run static analysis: `composer stan`
5. Run code style: `composer lint`
6. Run full quality check: `composer ready`

## Success Criteria

- ✅ All 100+ existing tests pass with namespace changes
- ✅ No new test failures introduced
- ✅ PHPStan level 5 passes
- ✅ Pint/Duster code style passes
- ✅ `composer ready` reports success
- ✅ No skipped or incomplete tests
