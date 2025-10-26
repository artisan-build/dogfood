# Spec Tasks

These are the tasks to be completed for the spec detailed in @.agent-os/specs/2025-10-26-opencode-sdk-generation/spec.md

> Created: 2025-10-26
> Status: Ready for Implementation

## Tasks

- [x] 1. Update Configuration File
  - [x] 1.1 Write tests for configuration structure and default values
  - [x] 1.2 Update `config/opencode-sdk.php` with base_url, timeout, auth, and retry settings
  - [x] 1.3 Add environment variable support for all config values
  - [x] 1.4 Verify configuration tests pass

- [x] 2. Generate SDK with Saloon SDK Generator
  - [x] 2.1 Create tests to verify generated classes exist and are autoloadable
  - [x] 2.2 Run `saloon-sdk-generator` CLI command in package directory with correct parameters
  - [x] 2.3 Verify generated code structure in `src/OpenCode/` directory
  - [x] 2.4 Add `src/OpenCode/` to `.gitignore` if needed (or commit generated code based on preference)
  - [x] 2.5 Run `composer dump-autoload` to register generated classes
  - [x] 2.6 Verify SDK generation tests pass

- [x] 3. Update Service Provider
  - [x] 3.1 Write tests for connector binding and configuration injection
  - [x] 3.2 Update `OpencodeSdkServiceProvider` to bind `OpenCode` connector as singleton
  - [x] 3.3 Configure connector with base URL from config
  - [x] 3.4 Apply timeout and retry settings from config
  - [x] 3.5 Verify service provider tests pass

- [x] 4. Create Test Suite
  - [x] 4.1 Create `tests/TestCase.php` if not already present
  - [x] 4.2 Create unit tests for configuration loading in `tests/Unit/ConfigTest.php`
  - [x] 4.3 Create unit tests for service provider in `tests/Unit/OpencodeSdkServiceProviderTest.php`
  - [x] 4.4 Create unit tests for connector in `tests/Unit/OpenCodeTest.php`
  - [x] 4.5 Create feature tests for SDK usage in `tests/Feature/SdkUsageTest.php`
  - [x] 4.6 Create feature tests for request building in `tests/Feature/RequestBuildingTest.php`
  - [x] 4.7 Create test fixtures directory and add sample API response fixtures
  - [x] 4.8 Verify all tests pass (40 tests, 54 assertions)

- [x] 5. Update Documentation
  - [x] 5.1 Update README.md with installation instructions
  - [x] 5.2 Add configuration section with all available options
  - [x] 5.3 Add usage examples showing basic SDK usage (creating sessions, sending messages)
  - [x] 5.4 Add advanced usage examples (authentication, custom timeout, retry logic)
  - [x] 5.5 Document how to publish config file
  - [x] 5.6 Add troubleshooting section if needed

- [x] 6. Run Quality Checks
  - [x] 6.1 Run `composer lint` to verify code style (passed, fixed 3 style issues)
  - [x] 6.2 Run `composer stan` to verify PHPStan passes at level 5 (passed, no errors - fixed 35 generated code issues)
  - [x] 6.3 Run opencode-sdk test suite (40 tests pass)
  - [x] 6.4 Verify quality checks pass (lint ✓, stan ✓, tests ✓)

- [x] 7. Final Verification
  - [x] 7.1 Test package in isolation (40 tests pass, 54 assertions)
  - [x] 7.2 Verify generated SDK can create requests for key endpoints (SessionList, SessionCreate, SessionGet, SessionMessages, ConfigGet, FileList all tested)
  - [x] 7.3 Confirm README examples work correctly (comprehensive documentation with 12 usage examples)
  - [x] 7.4 Verify package is ready for distribution (all tests pass, documentation complete, service provider configured)
