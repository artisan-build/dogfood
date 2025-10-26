# Technical Specification

This is the technical specification for the spec detailed in @.agent-os/specs/2025-10-26-opencode-nice-to-have-demo/spec.md

> Created: 2025-10-26
> Version: 1.0.0

## Technical Requirements

### Component Architecture

All components follow Livewire 3 full-page component pattern with:
- Server-side state management
- Real-time UI updates via Livewire polling where needed
- Flux UI Pro components for consistent styling
- Alpine.js for client-side interactivity where beneficial

### OpenCode SDK Integration

**Service Layer**

Uses the same shared `OpencodeService` class as high-impact components:

```php
class OpencodeService
{
    public function __construct(
        protected string $baseUrl = 'http://127.0.0.1:64415'
    ) {}

    public function client(): OpenCode
    {
        return new OpenCode(baseUrl: $this->baseUrl);
    }

    // Helper methods for nice-to-have operations
    public function getAgents(): array
    public function getTools(?string $provider = null, ?string $model = null): array
    public function getToolIds(): array
    public function getCommands(): array
    public function getConfig(): array
    public function updateConfig(array $config): array
    public function getProviders(): array
    public function setAuth(string $provider, string $key): bool
    public function subscribeToEvents(callable $handler): void
    public function writeLog(string $message, string $level = 'info'): bool
    public function getMcpStatus(): array
    public function initializeSession(string $directory): array
}
```

**Dependency Injection**

Inject `OpencodeService` into Livewire components:

```php
class OpencodeAgents extends Component
{
    public function __construct(
        protected OpencodeService $opencode
    ) {}
}
```

### Data Structures

**Agent Model** (In-memory, from API)
```php
[
    'id' => 'agent_xxx',
    'name' => 'Database Agent',
    'description' => 'Handles database schema and migrations',
    'capabilities' => ['schema_design', 'migration_generation'],
    'tools' => ['database_query', 'schema_analyzer'],
    'config' => [...],
]
```

**Tool Model** (In-memory)
```php
[
    'id' => 'tool_xxx',
    'name' => 'file_read',
    'description' => 'Read file contents from the project',
    'schema' => [
        'input' => [...],
        'output' => [...],
    ],
    'provider' => 'anthropic',
    'model' => 'claude-3-5-sonnet-20241022',
]
```

**Command Model** (In-memory)
```php
[
    'command' => '/analyze',
    'description' => 'Analyze project structure',
    'syntax' => '/analyze [path]',
    'examples' => [
        '/analyze src/',
        '/analyze --verbose',
    ],
]
```

**Config Model** (In-memory)
```php
[
    'version' => '1.0',
    'providers' => [
        'anthropic' => [
            'enabled' => true,
            'api_key' => '***',
            'models' => ['claude-3-5-sonnet-20241022'],
        ],
        'openai' => [
            'enabled' => false,
            'api_key' => null,
            'models' => ['gpt-4'],
        ],
    ],
    'defaults' => [
        'provider' => 'anthropic',
        'model' => 'claude-3-5-sonnet-20241022',
    ],
]
```

**Event Model** (In-memory)
```php
[
    'id' => 'evt_xxx',
    'type' => 'session.message',
    'timestamp' => '2025-10-26T12:34:56Z',
    'source' => 'opencode-tui',
    'severity' => 'info',
    'data' => [...],
]
```

## Component 1: Agent & Tool Dashboard

### Livewire Component Properties

```php
class OpencodeAgents extends Component
{
    // Connection
    public string $serverUrl = 'http://127.0.0.1:64415';

    // Agents
    public array $agents = [];
    public ?array $selectedAgent = null;

    // Tools
    public array $tools = [];
    public array $filteredTools = [];
    public ?string $selectedProvider = null;
    public ?string $selectedModel = null;

    // Commands
    public array $commands = [];

    // UI State
    public string $activeTab = 'agents'; // agents|tools|commands
    public string $searchQuery = '';
    public bool $loading = false;
    public ?string $error = null;

    // Tool Schema Viewer
    public bool $showSchemaModal = false;
    public ?array $selectedToolSchema = null;
}
```

### Key Methods

```php
// Data Loading
public function loadAgents(): void
public function loadTools(?string $provider = null, ?string $model = null): void
public function loadCommands(): void

// Agent Operations
public function selectAgent(string $agentId): void
public function getAgentDetails(string $agentId): array

// Tool Operations
public function filterTools(): void
public function viewToolSchema(string $toolId): void
public function closeSchemaModal(): void

// Command Operations
public function searchCommands(): void

// Tab Management
public function switchTab(string $tab): void
```

### UI Layout Structure

```
┌─────────────────────────────────────────────────────┐
│ Header: Agents & Tools                               │
├─────────────────────────────────────────────────────┤
│ [Agents] [Tools] [Commands]                         │
├─────────────────────────────────────────────────────┤
│ Search: [_________________] [🔍]                    │
├─────────────────────────────────────────────────────┤
│ ┌─────────────┐ ┌─────────────┐ ┌─────────────┐   │
│ │ 🤖 Agent 1  │ │ 🤖 Agent 2  │ │ 🤖 Agent 3  │   │
│ │ Description │ │ Description │ │ Description │   │
│ │ [View]      │ │ [View]      │ │ [View]      │   │
│ └─────────────┘ └─────────────┘ └─────────────┘   │
│                                                     │
│ Or (Tools Tab):                                     │
│ ┌─────────────────────────────────────────────────┐│
│ │ Tool Name          Provider    Model    [Schema]││
│ │ file_read          anthropic   claude   [View]  ││
│ │ database_query     openai      gpt-4    [View]  ││
│ └─────────────────────────────────────────────────┘│
├─────────────────────────────────────────────────────┤
│ Footer: {agent_count} agents, {tool_count} tools   │
└─────────────────────────────────────────────────────┘
```

## Component 2: Configuration Manager

### Livewire Component Properties

```php
class OpencodeConfig extends Component
{
    // Connection
    public string $serverUrl = 'http://127.0.0.1:64415';

    // Configuration
    public array $config = [];
    public string $configJson = '';
    public bool $editMode = false;

    // Providers
    public array $providers = [];
    public ?string $selectedProvider = null;

    // Forms
    public string $providerName = '';
    public string $apiKey = '';
    public bool $showApiKey = false;

    // UI State
    public string $activeTab = 'config'; // config|providers
    public bool $loading = false;
    public bool $saving = false;
    public ?string $error = null;
    public ?string $successMessage = null;

    // Connection Testing
    public array $connectionStatus = [];
}
```

### Key Methods

```php
// Configuration
public function loadConfig(): void
public function updateConfig(): void
public function toggleEditMode(): void
public function resetConfig(): void
public function exportConfig(): string
public function importConfig(string $json): void

// Provider Management
public function loadProviders(): void
public function selectProvider(string $provider): void
public function updateProvider(): void
public function testConnection(string $provider): void

// Authentication
public function setAuth(): void
public function toggleApiKeyVisibility(): void

// Tab Management
public function switchTab(string $tab): void
```

### UI Layout Structure

```
┌─────────────────────────────────────────────────────┐
│ Header: Configuration Manager                       │
├─────────────────────────────────────────────────────┤
│ [Configuration] [Providers]                         │
├─────────────────────────────────────────────────────┤
│ Configuration Tab:                                  │
│ ┌─────────────────────────────────────────────────┐│
│ │ {                                               ││
│ │   "version": "1.0",                             ││
│ │   "providers": {...},                           ││
│ │   "defaults": {...}                             ││
│ │ }                                               ││
│ │                                                 ││
│ │ [Edit] [Export] [Import]                        ││
│ └─────────────────────────────────────────────────┘│
│                                                     │
│ Providers Tab:                                      │
│ ┌───────────┐ ┌───────────┐ ┌───────────┐         │
│ │ Anthropic │ │ OpenAI    │ │ Local     │         │
│ │ ● Active  │ │ ○ Inactive│ │ ○ Inactive│         │
│ │ [Config]  │ │ [Config]  │ │ [Config]  │         │
│ └───────────┘ └───────────┘ └───────────┘         │
│                                                     │
│ Provider Config Form (when selected):               │
│ ┌─────────────────────────────────────────────────┐│
│ │ API Key: [*********************] [Show]         ││
│ │ Models: claude-3-5-sonnet-20241022              ││
│ │ [Test Connection] [Save]                        ││
│ └─────────────────────────────────────────────────┘│
└─────────────────────────────────────────────────────┘
```

## Component 3: Events Monitor

### Livewire Component Properties

```php
class OpencodeMonitor extends Component
{
    // Connection
    public string $serverUrl = 'http://127.0.0.1:64415';

    // Events
    public array $events = [];
    public array $filteredEvents = [];
    public array $eventTypes = [];
    public ?string $selectedEventType = null;
    public ?string $selectedSeverity = null;

    // Logs
    public string $logMessage = '';
    public string $logLevel = 'info';

    // MCP Status
    public ?array $mcpStatus = null;

    // UI State
    public string $activeTab = 'events'; // events|logs|mcp
    public bool $autoScroll = true;
    public bool $paused = false;
    public ?string $expandedEventId = null;

    // Polling
    public int $pollInterval = 2000; // ms
    public bool $loading = false;
    public ?string $error = null;
}
```

### Key Methods

```php
// Event Operations
public function subscribeToEvents(): void
public function loadEvents(): void
public function filterEvents(): void
public function toggleEvent(string $eventId): void
public function exportEvents(): string
public function clearEvents(): void

// Log Operations
public function writeLog(): void
public function loadLogs(): array

// MCP Operations
public function loadMcpStatus(): void
public function refreshMcpStatus(): void

// UI Controls
public function togglePause(): void
public function toggleAutoScroll(): void
public function switchTab(string $tab): void
```

### UI Layout Structure

```
┌─────────────────────────────────────────────────────┐
│ Header: Events Monitor          [Pause] [Clear]     │
├─────────────────────────────────────────────────────┤
│ [Events] [Logs] [MCP Status]                        │
├─────────────────────────────────────────────────────┤
│ Filters: Type [All ▼] Severity [All ▼]             │
├─────────────────────────────────────────────────────┤
│ Events Tab:                                         │
│ ┌─────────────────────────────────────────────────┐│
│ │ 12:34:56 [INFO] session.message                 ││
│ │   Session ses_123 received message              ││
│ │   [Expand ▼]                                    ││
│ ├─────────────────────────────────────────────────┤│
│ │ 12:34:55 [ERROR] api.request_failed             ││
│ │   Connection timeout to provider                ││
│ │   [Expand ▼]                                    ││
│ └─────────────────────────────────────────────────┘│
│                                                     │
│ Logs Tab:                                           │
│ ┌─────────────────────────────────────────────────┐│
│ │ Message: [__________________________________]   ││
│ │ Level: [info ▼]                                 ││
│ │ [Write Log]                                     ││
│ ├─────────────────────────────────────────────────┤│
│ │ Recent Logs:                                    ││
│ │ 12:34:56 [INFO] Custom log message              ││
│ └─────────────────────────────────────────────────┘│
│                                                     │
│ MCP Status Tab:                                     │
│ ┌─────────────────────────────────────────────────┐│
│ │ MCP Server: Running ✓                           ││
│ │ Protocol Version: 1.0                           ││
│ │ Active Connections: 3                           ││
│ │ Uptime: 2h 34m                                  ││
│ │ [Refresh]                                       ││
│ └─────────────────────────────────────────────────┘│
└─────────────────────────────────────────────────────┘
```

## Component 4: Session Initialization Wizard

### Livewire Component Properties

```php
class OpencodeInit extends Component
{
    // Wizard State
    public int $currentStep = 1;
    public int $totalSteps = 5;

    // Project Analysis
    public string $projectPath = '';
    public ?array $detectedProject = null;

    // Configuration
    public string $projectType = '';
    public array $configOptions = [];
    public string $agentsConfig = '';

    // Results
    public ?array $initResult = null;
    public bool $completed = false;

    // UI State
    public bool $analyzing = false;
    public bool $initializing = false;
    public ?string $error = null;
}
```

### Key Methods

```php
// Wizard Navigation
public function nextStep(): void
public function previousStep(): void
public function goToStep(int $step): void

// Project Operations
public function analyzeProject(): void
public function detectProjectType(): string

// Configuration
public function updateConfig(string $key, mixed $value): void
public function generateAgentsConfig(): string
public function previewAgentsConfig(): void

// Initialization
public function initializeSession(): void
public function completeWizard(): void
public function startOver(): void
```

### UI Layout Structure

```
┌─────────────────────────────────────────────────────┐
│ Header: Session Initialization Wizard               │
├─────────────────────────────────────────────────────┤
│ Progress: [█████░░░░░] Step 2 of 5                  │
├─────────────────────────────────────────────────────┤
│ Step 1: Select Project Directory                    │
│ ┌─────────────────────────────────────────────────┐│
│ │ Path: [/path/to/project    ] [Browse]           ││
│ │ [Analyze Project]                                ││
│ └─────────────────────────────────────────────────┘│
│                                                     │
│ Step 2: Review Detected Configuration              │
│ ┌─────────────────────────────────────────────────┐│
│ │ Project Type: Laravel Application               ││
│ │ Framework: Laravel 11                           ││
│ │ Language: PHP 8.3                               ││
│ │ Database: SQLite                                ││
│ │ ☑ Auto-detected settings correct                ││
│ └─────────────────────────────────────────────────┘│
│                                                     │
│ Step 3: Customize Configuration                    │
│ ┌─────────────────────────────────────────────────┐│
│ │ Provider: [anthropic ▼]                         ││
│ │ Model: [claude-3-5-sonnet-20241022 ▼]          ││
│ │ Agent Role: [code_assistant ▼]                  ││
│ └─────────────────────────────────────────────────┘│
│                                                     │
│ Step 4: Preview AGENTS.md                          │
│ ┌─────────────────────────────────────────────────┐│
│ │ # Agent Configuration                           ││
│ │                                                 ││
│ │ Provider: anthropic                             ││
│ │ Model: claude-3-5-sonnet-20241022               ││
│ │ ...                                             ││
│ └─────────────────────────────────────────────────┘│
│                                                     │
│ Step 5: Initialize                                 │
│ ┌─────────────────────────────────────────────────┐│
│ │ ✓ Configuration validated                       ││
│ │ ✓ AGENTS.md generated                           ││
│ │ ✓ Session initialized                           ││
│ │ [Complete Setup]                                ││
│ └─────────────────────────────────────────────────┘│
│                                                     │
│ [Previous] [Next]                                   │
└─────────────────────────────────────────────────────┘
```

## External Dependencies

### JSON Editor
**Library**: jsoneditor
**Installation**: `npm install jsoneditor`
**Usage**: Configuration editing in Config Manager
**Justification**: Provides syntax highlighting, validation, and easy editing for JSON config

### Event Streaming
**Approach**: Server-Sent Events (SSE) via Livewire polling
**Implementation**: Poll EventSubscribe endpoint every 2 seconds
**Justification**: Real-time events without WebSocket complexity

### Syntax Highlighting
**Library**: highlight.js (shared with high-impact components)
**Usage**: Tool schemas, config files, AGENTS.md preview
**Justification**: Already included for other components

## Performance Considerations

### Caching Strategy
- Cache agent list for 5 minutes (rarely changes)
- Cache tool list for 1 minute per provider/model combination
- Cache config for 30 seconds (changes infrequent)
- Cache MCP status for 10 seconds
- No caching for events (real-time)

### Lazy Loading
- Load agent details only when selected
- Load tool schemas only when viewed
- Defer event subscription until Events tab active
- Load MCP status only when tab selected

### Polling Strategy
- Poll events only when Events tab active and not paused
- Poll MCP status every 10 seconds when tab active
- Stop all polling when component not visible (Livewire lifecycle)
- Limit event history to last 100 events (configurable)

## Error Handling

### Connection Failures
Display friendly error messages with:
- What went wrong
- How to fix it (e.g., "Make sure OpenCode server is running")
- Retry button

### API Errors
Catch and display:
- Network errors
- HTTP error statuses with response body
- Timeout errors (show spinner max 30s)
- Invalid responses (malformed JSON)

### Validation
Client-side validation for:
- Empty configuration fields
- Invalid JSON in config editor
- Missing required provider credentials
- Invalid project paths in wizard
- Malformed log messages

## Security Considerations

### Credential Handling
- Mask API keys by default (show button to reveal)
- Never log API keys in events or logs
- Validate credential format before sending
- Clear sensitive data from browser memory after use

### Input Sanitization
- Escape all user input before display
- Validate JSON before parsing
- Sanitize file paths in wizard
- Prevent XSS in event data display

### CSRF Protection
- Use Livewire's built-in CSRF protection
- No additional tokens needed

## Browser Compatibility

**Target**: Modern browsers for conference demos
- Chrome 120+
- Firefox 120+
- Safari 17+
- Edge 120+

**Not supporting**: IE11, older mobile browsers

## Accessibility (Future Enhancement)

Not a priority for conference demo, but note for production:
- Keyboard navigation
- Screen reader support
- ARIA labels
- Focus management
