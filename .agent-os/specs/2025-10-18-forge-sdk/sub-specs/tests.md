# Tests Specification

This is the tests coverage details for the spec detailed in @.agent-os/specs/2025-10-18-forge-sdk/spec.md

> Created: 2025-10-18
> Version: 1.0.0

## Test Coverage

### Unit Tests

**ForgeConnector**
- Test connector sets correct base URL
- Test connector includes API token in Authorization header
- Test connector handles missing API token gracefully
- Test connector timeout configuration

**Enum Validation**
- Test each enum validates valid values successfully
- Test each enum throws exception for invalid values
- Test enum tryFrom() returns null for invalid values
- Test enum cases() returns all available options

**Request Classes** (for each endpoint)
- Test request sets correct HTTP method
- Test request sets correct endpoint path
- Test request validates required parameters
- Test request validates optional parameters
- Test request throws exception for invalid enum values
- Test request body construction with all parameters
- Test request query parameter construction where applicable

**Resource Classes**
- Test resource instantiates with connector
- Test resource methods return correct request instances
- Test resource methods pass parameters to requests correctly

**Exception Classes**
- Test ValidationException message formatting
- Test ForgeException wraps API errors correctly

### Integration Tests

**Server Resource Workflows**
- Test complete server creation workflow (request composition)
- Test server listing with pagination
- Test server retrieval by ID
- Test server update workflow
- Test server deletion workflow
- Test server reboot workflow

**Site Resource Workflows**
- Test site creation on server
- Test site listing for server
- Test site retrieval by ID
- Test site update workflow
- Test site deletion workflow

**SSL Certificate Workflows**
- Test Let's Encrypt certificate installation
- Test existing certificate installation
- Test certificate activation
- Test certificate listing for site
- Test certificate deletion

**Database Workflows**
- Test database creation on server
- Test database user creation
- Test database user listing
- Test database listing
- Test database deletion

**Daemon Workflows**
- Test daemon creation with all parameters
- Test daemon restart
- Test daemon listing
- Test daemon deletion

**Worker Workflows**
- Test worker creation for site
- Test worker restart
- Test worker listing for site
- Test worker deletion

**Scheduled Job Workflows**
- Test scheduled job creation with different frequencies
- Test custom cron schedule creation
- Test scheduled job listing
- Test scheduled job deletion

**Deployment Workflows**
- Test deployment trigger
- Test deployment history retrieval
- Test deployment log retrieval
- Test deployment script update
- Test deployment script retrieval
- Test deployment reset

**Recipe Workflows**
- Test recipe creation
- Test recipe update
- Test recipe listing
- Test recipe deletion
- Test recipe execution on server

**Credential Workflows**
- Test credential creation for different types
- Test credential listing
- Test credential retrieval
- Test credential deletion

**Webhook Workflows**
- Test webhook creation for site
- Test webhook listing for site
- Test webhook deletion

**Backup Workflows**
- Test backup configuration creation
- Test backup execution
- Test backup configuration listing
- Test backup configuration deletion

**Circle Workflows**
- Test circle creation
- Test circle listing
- Test circle retrieval
- Test circle deletion

**Firewall Rule Workflows**
- Test firewall rule creation with port
- Test firewall rule creation with IP address
- Test firewall rule listing
- Test firewall rule deletion

**SSH Key Workflows**
- Test SSH key addition to server
- Test SSH key listing for server
- Test SSH key deletion

### Feature Tests

**End-to-End Server Setup**
- Test creating server with all options
- Test creating site on new server
- Test installing SSL certificate on site
- Test configuring database on server
- Test adding firewall rules
- Test complete cleanup (deletion)

**End-to-End Site Deployment**
- Test creating site with Git repository
- Test updating deployment script
- Test triggering deployment
- Test retrieving deployment log
- Test configuring queue worker
- Test setting up scheduled jobs

**End-to-End Backup Configuration**
- Test creating backup configuration
- Test running manual backup
- Test verifying backup completion

### Service Provider Tests

**ForgeServiceProvider**
- Test service provider registers connector
- Test service provider publishes config
- Test service provider registers commands
- Test config file structure and defaults

**Artisan Command Tests** (for each command)
- Test command signature is correct
- Test command requires correct arguments
- Test command accepts correct options
- Test command validates input before API call
- Test command displays formatted output
- Test command handles API errors gracefully

### Mocking Requirements

**Saloon MockClient**
- Use Saloon's MockClient for all HTTP requests in tests
- Create fixture responses for each endpoint
- Mock successful responses (200, 201, 204)
- Mock error responses (401, 404, 422, 429)
- Mock paginated responses where applicable

**Fixture Structure**
```
tests/Fixtures/
├── responses/
│   ├── servers/
│   │   ├── list.json
│   │   ├── show.json
│   │   ├── create.json
│   │   ├── update.json
│   │   └── errors/
│   │       ├── 404.json
│   │       └── 422.json
│   ├── sites/
│   │   ├── list.json
│   │   ├── show.json
│   │   └── ...
│   └── ...
```

**Mock Strategy**
- Load fixture JSON files in test setup
- Create MockResponse objects from fixtures
- Assert requests match expected method, endpoint, and parameters
- Return appropriate fixture based on request

**Example Mock Pattern**:
```php
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

$mockClient = new MockClient([
    GetServersRequest::class => MockResponse::make(
        body: file_get_contents(__DIR__ . '/Fixtures/responses/servers/list.json'),
        status: 200
    ),
]);

$connector = new ForgeConnector(
    token: 'test-token',
    mockClient: $mockClient
);
```

### Test Organization

**Directory Structure**:
```
tests/
├── Unit/
│   ├── Enums/
│   │   ├── CloudProviderTest.php
│   │   ├── ServerTypeTest.php
│   │   └── ...
│   ├── Requests/
│   │   ├── Servers/
│   │   │   ├── GetServersRequestTest.php
│   │   │   ├── CreateServerRequestTest.php
│   │   │   └── ...
│   │   ├── Sites/
│   │   └── ...
│   ├── Resources/
│   │   ├── ServerResourceTest.php
│   │   ├── SiteResourceTest.php
│   │   └── ...
│   └── ForgeConnectorTest.php
├── Feature/
│   ├── ServerWorkflowsTest.php
│   ├── SiteWorkflowsTest.php
│   ├── DeploymentWorkflowsTest.php
│   └── ...
├── Commands/
│   ├── TestServerCommandTest.php
│   ├── TestSiteCommandTest.php
│   └── ...
├── Fixtures/
│   └── responses/
│       └── (JSON fixtures)
└── Pest.php
```

### Coverage Goals

- **Line Coverage**: Aim for 90%+ on core SDK code
- **Branch Coverage**: Ensure all validation paths are tested
- **Edge Cases**: Test boundary conditions, empty responses, pagination edge cases
- **Error Handling**: Test all exception throwing scenarios
- **Integration Points**: Test all resource methods integrate with connector correctly

### Performance Tests (Optional)

While not required for MVP, consider adding:
- Test response parsing performance with large datasets
- Test pagination handling with many pages
- Test concurrent request handling

### Documentation Tests

- Test that all public methods have PHPDoc blocks
- Test that README examples are syntactically correct
- Consider using phpDocumentor to verify documentation completeness
