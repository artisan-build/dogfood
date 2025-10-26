# Tests Specification

This is the tests coverage details for the spec detailed in @.agent-os/specs/2025-10-26-opencode-nice-to-have-demo/spec.md

> Created: 2025-10-26
> Version: 1.0.0

## Test Coverage

### Unit Tests

**OpencodeService (Nice-to-Have Methods)**
- Can get agents list
- Can get tools list with optional provider/model filters
- Can get tool IDs
- Can get commands list
- Can get configuration
- Can update configuration
- Can get providers list
- Can set authentication credentials
- Can subscribe to events
- Can write log entries
- Can get MCP status
- Can initialize session
- Handles API errors gracefully for all endpoints
- Returns proper error messages on failure

### Feature Tests

#### Agent & Tool Dashboard Tests

**Agent Discovery**
- Can render agents component
- Can load all agents
- Can display agent grid
- Can select agent to view details
- Agent details show capabilities and tools
- Can search agents by name
- Can filter agents by capability
- Agent count displays correctly

**Tool Registry**
- Can load all tools
- Can filter tools by provider
- Can filter tools by model
- Can search tools by name
- Tool table displays correctly
- Can view tool schema in modal
- Schema modal shows JSON with syntax highlighting
- Can close schema modal

**Command Catalog**
- Can load all commands
- Commands display with syntax
- Can search commands
- Command examples show correctly
- Can copy command syntax

**Tab Management**
- Can switch between Agents tab
- Can switch between Tools tab
- Can switch between Commands tab
- Active tab state persists
- Data loads when tab activated

**Error Handling**
- Displays error when server unavailable
- Displays error on invalid data
- Shows loading state during fetch
- Retry button works after error

#### Configuration Manager Tests

**Configuration Viewing**
- Can render config component
- Can load current configuration
- Configuration displays as formatted JSON
- Syntax highlighting works
- Line numbers display

**Configuration Editing**
- Can enter edit mode
- Can update configuration
- Can save changes
- Can cancel changes (reverts to original)
- Validation prevents invalid JSON
- Success message shows after save

**Provider Management**
- Can load all providers
- Provider cards display with status
- Can select provider to configure
- Provider details show models and settings
- Active provider shows as connected
- Inactive provider shows as disconnected

**Credential Management**
- Can input API key
- API key masked by default
- Can toggle API key visibility
- Can save credentials
- Success confirmation after save
- Can test connection
- Connection test shows success/failure

**Export/Import**
- Can export configuration as JSON
- Export triggers download
- Can import configuration file
- Import validates JSON format
- Import updates configuration
- Import shows success/error

**Tab Management**
- Can switch between Config tab
- Can switch between Providers tab
- Active tab state persists

**Error Handling**
- Displays error when server unavailable
- Displays error on invalid configuration
- Displays error on failed connection test
- Shows validation errors for credentials

#### Events Monitor Tests

**Event Streaming**
- Can render monitor component
- Can subscribe to event stream
- Events display in real-time
- New events appear at top
- Auto-scroll works when enabled
- Can pause event stream
- Can resume event stream
- Event count displays correctly

**Event Filtering**
- Can filter by event type
- Can filter by severity
- Can apply multiple filters
- Filtered count updates
- Can clear filters
- Filter dropdown shows all types

**Event Display**
- Events show timestamp
- Events show type and severity
- Can expand event for details
- Expanded view shows full payload
- Can collapse event
- Severity colors display correctly (info=blue, error=red, etc)

**Event Export**
- Can export events as JSON
- Export includes filtered events only
- Export triggers download
- Can clear event history
- Clear requires confirmation

**Log Viewer**
- Can load recent logs
- Logs display with timestamps
- Logs show severity levels
- Can write custom log entry
- Log level dropdown works
- Success message after writing log
- New logs appear in list

**MCP Status**
- Can load MCP status
- Status displays server info
- Shows connection count
- Shows protocol version
- Shows uptime
- Can refresh status manually
- Refresh updates data
- Shows error if MCP unavailable

**Tab Management**
- Can switch between Events tab
- Can switch between Logs tab
- Can switch between MCP Status tab
- Active tab state persists
- Events pause when tab inactive

**Error Handling**
- Displays error when server unavailable
- Displays error on event subscription failure
- Displays error if log write fails
- Shows MCP unavailable message
- Retry button works after error

#### Session Initialization Wizard Tests

**Wizard Navigation**
- Can render wizard component
- Shows step 1 by default
- Progress bar shows current step
- Can navigate to next step
- Can navigate to previous step
- Can jump to specific step (if allowed)
- Next button disabled if step incomplete
- Previous button disabled on step 1
- Progress bar updates correctly

**Project Analysis**
- Can input project path
- Can browse for directory
- Can analyze project structure
- Analysis detects project type
- Analysis detects framework
- Analysis detects language version
- Analysis detects database
- Analysis results display correctly
- Can proceed after successful analysis

**Configuration Review**
- Shows detected configuration
- Each detected item displayed
- Can mark detection as correct/incorrect
- Can override detected values
- Override form appears when needed
- Can save overrides
- Overrides persist when navigating

**Configuration Customization**
- Shows configuration options
- Provider dropdown shows available providers
- Model dropdown filtered by provider
- Agent role dropdown shows options
- Can update each configuration field
- Changes reflect in preview
- Validation prevents invalid choices

**AGENTS.md Preview**
- Generates AGENTS.md content
- Shows preview with syntax highlighting
- Preview updates when config changes
- Can edit preview directly
- Can reset to generated version
- Shows file will be saved to project

**Initialization**
- Can initialize session
- Shows initialization progress
- Progress indicates each step
- Completion shows success message
- Displays session ID after init
- Can view generated files
- Can start over (resets wizard)

**Error Handling**
- Shows error if project path invalid
- Shows error if analysis fails
- Shows error if initialization fails
- Can retry failed steps
- Error messages are actionable
- Can cancel wizard at any step

### Integration Tests

**End-to-End Agent Discovery Flow**
- User can load agents, select one, view tools it uses, and see tool schemas
- Switching between tabs maintains state
- Search works across all tabs

**End-to-End Configuration Flow**
- User can view config, switch to providers, add credentials, test connection, and save
- Edited configuration persists
- Export and re-import maintains config

**End-to-End Event Monitoring Flow**
- User can subscribe to events, filter by type, expand event, write log, and check MCP
- Events continue streaming while user interacts
- Pause prevents new events from appearing

**End-to-End Initialization Flow**
- User can analyze project, review detection, customize config, preview AGENTS.md, and complete init
- Wizard maintains state when navigating back
- Generated files appear in project

### Browser Tests (Manual/Automated with Dusk)

**Visual Regression**
- Agents dashboard matches design mockups
- Config manager matches design mockups
- Events monitor matches design mockups
- Init wizard matches design mockups

**Responsive Behavior**
- Layout adjusts for projector resolution (1920x1080)
- Components readable from conference room distance
- No horizontal scrolling on standard screens
- Tab navigation works on mobile

**Cross-Browser**
- Works in Chrome 120+
- Works in Firefox 120+
- Works in Safari 17+

### Mocking Requirements

**OpenCode API Mocking**
- Mock all OpenCode SDK responses for tests
- Use factories for consistent test data
- Mock agent responses
- Mock tool responses
- Mock command responses
- Mock config responses
- Mock provider responses
- Mock event stream
- Mock log responses
- Mock MCP status responses
- Mock initialization responses

**Example Mock Data:**

```php
// Mock agents response
[
    [
        'id' => 'agent_test1',
        'name' => 'Test Agent',
        'description' => 'A test agent for unit tests',
        'capabilities' => ['testing', 'mocking'],
        'tools' => ['test_tool_1', 'test_tool_2'],
    ],
]

// Mock tools response
[
    [
        'id' => 'tool_test1',
        'name' => 'test_tool',
        'description' => 'A test tool',
        'schema' => [
            'input' => ['type' => 'object', 'properties' => [...]],
            'output' => ['type' => 'object', 'properties' => [...]],
        ],
        'provider' => 'anthropic',
        'model' => 'claude-3-5-sonnet-20241022',
    ],
]

// Mock commands response
[
    [
        'command' => '/test',
        'description' => 'A test command',
        'syntax' => '/test [args]',
        'examples' => ['/test example'],
    ],
]

// Mock config response
[
    'version' => '1.0',
    'providers' => [
        'anthropic' => [
            'enabled' => true,
            'api_key' => 'test_key',
            'models' => ['claude-3-5-sonnet-20241022'],
        ],
    ],
    'defaults' => [
        'provider' => 'anthropic',
        'model' => 'claude-3-5-sonnet-20241022',
    ],
]

// Mock event response
[
    'id' => 'evt_test1',
    'type' => 'session.message',
    'timestamp' => '2025-10-26T12:00:00Z',
    'source' => 'opencode-tui',
    'severity' => 'info',
    'data' => ['message' => 'Test event'],
]

// Mock MCP status response
[
    'status' => 'running',
    'version' => '1.0',
    'connections' => 3,
    'uptime' => 7200,
]

// Mock init response
[
    'session_id' => 'ses_test123',
    'project_type' => 'laravel',
    'framework' => 'Laravel 11',
    'language' => 'PHP 8.3',
    'agents_config' => '# Agent Configuration...',
]
```

### Performance Tests

**Load Testing**
- Component renders within 2 seconds
- Agent list with 50 agents loads quickly
- Tool list with 200 tools navigable
- Event stream handles 100 events without lag
- Config editor loads 1000-line JSON without freeze

**Memory Testing**
- No memory leaks on repeated operations
- Event history doesn't grow unbounded (limit to 100)
- Modal cleanup releases resources
- Component unmount clears subscriptions

### Test Organization

```
packages/opencode-client/tests/
├── Unit/
│   ├── Services/
│   │   └── OpencodeServiceNiceToHaveTest.php
│   └── Helpers/
│       └── JsonFormatterTest.php
├── Feature/
│   ├── OpencodeAgents/
│   │   ├── AgentDiscoveryTest.php
│   │   ├── ToolRegistryTest.php
│   │   ├── CommandCatalogTest.php
│   │   └── ErrorHandlingTest.php
│   ├── OpencodeConfig/
│   │   ├── ConfigViewingTest.php
│   │   ├── ConfigEditingTest.php
│   │   ├── ProviderManagementTest.php
│   │   └── CredentialManagementTest.php
│   ├── OpencodeMonitor/
│   │   ├── EventStreamingTest.php
│   │   ├── EventFilteringTest.php
│   │   ├── LogViewerTest.php
│   │   └── McpStatusTest.php
│   └── OpencodeInit/
│       ├── WizardNavigationTest.php
│       ├── ProjectAnalysisTest.php
│       ├── ConfigurationTest.php
│       └── InitializationTest.php
└── Browser/
    ├── AgentFlowTest.php
    ├── ConfigFlowTest.php
    ├── MonitorFlowTest.php
    └── InitFlowTest.php
```

### Continuous Integration

**Pre-commit Checks**
- All tests must pass
- No PHPStan errors
- Code formatted with Pint
- No obvious security issues

**CI Pipeline**
- Run full test suite on PR
- Run browser tests nightly
- Generate coverage report (target: 75%+)

### Test Data Management

**Fixtures**
- Sample agent data in `tests/fixtures/agents/`
- Sample tool schemas in `tests/fixtures/tools/`
- Sample config files in `tests/fixtures/configs/`
- Sample event streams in `tests/fixtures/events/`

**Database**
- Use in-memory SQLite for tests
- No database persistence needed (all in-memory)
- Migrations not required for demo

### Coverage Goals

- **Unit Tests**: 85%+ coverage of OpencodeService nice-to-have methods
- **Feature Tests**: 80%+ coverage of Livewire components
- **Integration Tests**: All critical user flows covered
- **Browser Tests**: All major user interactions verified

### Test Execution

```bash
# Run all tests
composer test

# Run specific test suite
php artisan test packages/opencode-client/tests/Feature/OpencodeAgents

# Run with coverage
composer coverage

# Run browser tests
php artisan dusk packages/opencode-client/tests/Browser
```

### Special Testing Considerations

**Event Streaming**
- Mock SSE stream for predictable testing
- Test pause/resume behavior
- Test event limit enforcement
- Test auto-scroll behavior

**Configuration Editing**
- Test JSON validation thoroughly
- Test various invalid JSON scenarios
- Test large config files (1000+ lines)
- Test special characters in config values

**Provider Credentials**
- Test masked display of sensitive data
- Test toggle visibility
- Test credential validation formats
- Never log actual credentials in tests

**Wizard State Management**
- Test navigation between steps
- Test state persistence
- Test step validation
- Test cancellation at each step
