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

- [ ] 3. Update Service Provider
  - [ ] 3.1 Write tests for connector binding and configuration injection
  - [ ] 3.2 Update `OpencodeSdkServiceProvider` to bind `OpenCodeConnector` as singleton
  - [ ] 3.3 Configure connector with base URL from config
  - [ ] 3.4 Apply timeout and retry settings from config
  - [ ] 3.5 Verify service provider tests pass

- [ ] 4. Create Test Suite
  - [ ] 4.1 Create `tests/TestCase.php` if not already present
  - [ ] 4.2 Create unit tests for configuration loading in `tests/Unit/ConfigTest.php`
  - [ ] 4.3 Create unit tests for service provider in `tests/Unit/OpencodeSdkServiceProviderTest.php`
  - [ ] 4.4 Create unit tests for connector in `tests/Unit/OpenCodeConnectorTest.php`
  - [ ] 4.5 Create feature tests for SDK usage in `tests/Feature/SdkUsageTest.php`
  - [ ] 4.6 Create feature tests for request building in `tests/Feature/RequestBuildingTest.php`
  - [ ] 4.7 Create test fixtures directory and add sample API response fixtures
  - [ ] 4.8 Verify all tests pass with `composer test`

- [ ] 5. Update Documentation
  - [ ] 5.1 Update README.md with installation instructions
  - [ ] 5.2 Add configuration section with all available options
  - [ ] 5.3 Add usage examples showing basic SDK usage (creating sessions, sending messages)
  - [ ] 5.4 Add advanced usage examples (authentication, custom timeout, retry logic)
  - [ ] 5.5 Document how to publish config file
  - [ ] 5.6 Add troubleshooting section if needed

- [ ] 6. Run Quality Checks
  - [ ] 6.1 Run `composer lint` to verify code style
  - [ ] 6.2 Run `composer stan` to verify PHPStan passes at level 5
  - [ ] 6.3 Run full test suite from monorepo root with `composer test -- --filter="OpencodeSdk"`
  - [ ] 6.4 Verify all quality checks pass with `composer ready`

- [ ] 7. Final Verification
  - [ ] 7.1 Test package in isolation by running `cd packages/opencode-sdk && composer install && composer test`
  - [ ] 7.2 Verify generated SDK can create requests for key endpoints (session list, create, message)
  - [ ] 7.3 Confirm README examples work correctly
  - [ ] 7.4 Verify package is ready for distribution via `composer require artisan-build/opencode-sdk`
