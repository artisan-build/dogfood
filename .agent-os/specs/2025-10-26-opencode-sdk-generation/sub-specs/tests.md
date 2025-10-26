# Tests Specification

This is the tests coverage details for the spec detailed in @.agent-os/specs/2025-10-26-opencode-sdk-generation/spec.md

> Created: 2025-10-26
> Version: 1.0.0

## Test Coverage

### Unit Tests

**OpencodeSdkServiceProvider**
- Test config file is registered with correct key
- Test config publishing is configured
- Test connector is bound to the container as singleton
- Test connector receives correct configuration values
- Test same connector instance is returned on multiple resolutions

**OpenCodeConnector (Generated)**
- Test connector initializes with correct base URL from config
- Test connector has correct namespace and class name
- Test connector is instantiable
- Test base URL can be retrieved
- Test timeout is applied from config

### Integration Tests

**Configuration Loading**
- Test config file returns expected array structure
- Test default values are set correctly
- Test environment variables override config defaults
- Test auth configuration is optional and nullable

**Service Provider Registration**
- Test service provider is discoverable by Laravel
- Test config merging works correctly
- Test connector can be resolved from container
- Test connector has correct configuration when resolved

**SDK Generation Verification**
- Test generated connector class exists at expected path
- Test generated resource classes exist
- Test generated request classes are created
- Test PSR-4 autoloading works for generated classes

### Feature Tests

**Basic SDK Usage**
- Test creating a connector instance manually
- Test resolving connector from Laravel container
- Test connector can create resource instances
- Test resource can create request instances (mocked)

**Request Building**
- Test session list request can be built
- Test session create request can be built with parameters
- Test config get request can be built
- Test file list request can be built with path parameter

### Mocking Requirements

All tests should use Saloon's built-in testing utilities:

- **Saloon\Http\Faking\MockClient** - Mock HTTP responses without hitting real API
- **Saloon\Http\Faking\MockResponse** - Create fake responses with status codes and JSON data
- **Saloon\Http\Faking\Fixture** - Use fixture files for complex response mocking

Example mocking pattern:
```php
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

$mockClient = new MockClient([
    MockResponse::make(['id' => 'ses_123', 'title' => 'Test'], 200),
]);

$connector = new OpenCodeConnector();
$connector->withMockClient($mockClient);

// Make request and assert response
```

### Test Organization

```
packages/opencode-sdk/tests/
├── Unit/
│   ├── OpencodeSdkServiceProviderTest.php
│   ├── OpenCodeConnectorTest.php
│   └── ConfigTest.php
├── Feature/
│   ├── SdkUsageTest.php
│   └── RequestBuildingTest.php
└── Pest.php
```

### Test Configuration

Tests should use the `TestCase` class that's already configured for Orchestra Testbench:

```php
<?php

use ArtisanBuild\OpencodeSdk\Tests\TestCase;

uses(TestCase::class)->in(__DIR__);
```

### Coverage Goals

- **Unit Tests**: 100% coverage of service provider and configuration loading
- **Integration Tests**: Verify all generated classes are present and autoloadable
- **Feature Tests**: Verify basic SDK usage patterns work with mocked responses

### Test Data

Create fixture files for common API responses:
- `tests/Fixtures/session-list.json` - Example session list response
- `tests/Fixtures/session-created.json` - Example session creation response
- `tests/Fixtures/message-response.json` - Example message response
- `tests/Fixtures/config-response.json` - Example config response

### PHPStan Integration

All generated code and tests should pass PHPStan level 5 analysis:
```bash
composer stan
```

### Test Execution

Tests should be runnable from:
1. **Monorepo root**: `composer test -- --filter="OpencodeSdk"`
2. **Package directory**: `cd packages/opencode-sdk && composer test`

Both contexts should work seamlessly due to dual testing setup.
