# Technical Specification

This is the technical specification for the spec detailed in @.agent-os/specs/2025-10-26-opencode-high-impact-demo/spec.md

> Created: 2025-10-26
> Version: 1.0.0

## Technical Requirements

### Component Architecture

All components follow Livewire 3 full-page component pattern with:
- Server-side state management
- Real-time UI updates via Livewire polling (2s intervals for active operations)
- Flux UI Pro components for consistent styling
- Alpine.js for client-side interactivity where needed

### OpenCode SDK Integration

**Service Layer**
Create shared `OpencodeService` class in `packages/opencode-client/src/Services/`:

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

    // Helper methods for common operations
    public function createSession(?string $directory = null): array
    public function listSessions(?string $directory = null): array
    public function sendPrompt(string $sessionId, string $prompt): array
    public function forkSession(string $sessionId, string $messageId): array
    // ... etc
}
```

**Dependency Injection**
Inject `OpencodeService` into Livewire components:

```php
class OpencodeChat extends Component
{
    public function __construct(
        protected OpencodeService $opencode
    ) {}
}
```

### Data Structures

**Session Model** (In-memory, not database)
```php
[
    'id' => 'ses_xxx',
    'created_at' => '2025-10-26T...',
    'parent_id' => 'ses_yyy', // for forked sessions
    'fork_message_id' => 'msg_zzz', // message where fork occurred
    'children' => ['ses_aaa', 'ses_bbb'], // child session IDs
    'message_count' => 15,
    'last_message' => '...',
]
```

**Message Model** (In-memory)
```php
[
    'id' => 'msg_xxx',
    'session_id' => 'ses_yyy',
    'role' => 'user|assistant',
    'content' => '...',
    'parts' => [...], // full parts array from API
    'created_at' => '...',
    'has_diff' => true|false,
    'is_reverted' => true|false,
]
```

## Component 1: Enhanced Chat Interface

### Livewire Component Properties

```php
class OpencodeChat extends Component
{
    // Connection
    public string $serverUrl = 'http://127.0.0.1:64415';
    public ?string $currentSessionId = null;

    // Sessions
    public array $sessions = [];
    public array $sessionTree = []; // hierarchical structure for visualization

    // Messages
    public array $messages = [];
    public string $messageInput = '';

    // UI State
    public bool $connecting = false;
    public bool $sending = false;
    public bool $showSessionSidebar = true;
    public bool $showDiff = false;
    public ?string $activeDiffMessageId = null;
    public ?string $error = null;

    // Actions
    public array $todos = [];
    public bool $showTodos = false;
}
```

### Key Methods

```php
// Session Management
public function loadSessions(): void
public function createSession(): void
public function selectSession(string $sessionId): void
public function deleteSession(string $sessionId): void
public function forkSession(string $messageId): void
public function renameSession(string $sessionId, string $name): void

// Session Actions
public function abortSession(): void
public function summarizeSession(): void
public function shareSession(): void
public function unshareSession(): void

// Messages
public function sendMessage(): void
public function loadMessages(): void
public function revertMessage(string $messageId): void
public function unrevertMessage(string $messageId): void
public function viewDiff(string $messageId): void

// Todos
public function loadTodos(): void
public function toggleTodo(int $index): void

// Shell
public function executeShellCommand(string $command): void
```

### UI Layout Structure

```
┌─────────────────────────────────────────────────────┐
│ Header: OpenCode Chat                                │
├──────────┬──────────────────────────────────────────┤
│ Sessions │ Current Session: {session_name}          │
│ Sidebar  │ ┌──────────────────────────────────────┐│
│          │ │ Messages                             ││
│ • Ses 1  │ │ ┌──────────────────────────────────┐││
│ • Ses 2  │ │ │ User: Hello                       │││
│   ├─ 2a  │ │ │ [Fork] [Diff] [Revert]           │││
│   └─ 2b  │ │ └──────────────────────────────────┘││
│ • Ses 3  │ │ ┌──────────────────────────────────┐││
│          │ │ │ Assistant: Hi there!              │││
│ [New]    │ │ │ [Fork] [Diff]                    │││
│          │ │ └──────────────────────────────────┘││
│          │ └──────────────────────────────────────┘│
│          │ ┌──────────────────────────────────────┐│
│          │ │ Textarea for input                    ││
│          │ │ [Send] [Shell] [Todos] [Actions]     ││
│          │ └──────────────────────────────────────┘│
├──────────┴──────────────────────────────────────────┤
│ Footer: Powered by OpenCode SDK                      │
└─────────────────────────────────────────────────────┘
```

## Component 2: Project Explorer

### Livewire Component Properties

```php
class OpencodeExplorer extends Component
{
    // File Browser
    public string $currentPath = '/';
    public array $directoryContents = [];
    public array $breadcrumbs = [];
    public ?array $currentFile = null; // ['path' => '...', 'content' => '...']

    // Projects
    public array $projects = [];
    public ?string $currentProject = null;

    // Search
    public string $searchQuery = '';
    public string $searchType = 'text'; // text|files|symbols
    public array $searchResults = [];
    public bool $searching = false;

    // UI State
    public array $expandedDirs = [];
    public bool $loading = false;
    public ?string $error = null;
}
```

### Key Methods

```php
// File Navigation
public function listDirectory(string $path): void
public function openFile(string $path): void
public function navigateToPath(string $path): void
public function toggleDirectory(string $path): void

// Projects
public function loadProjects(): void
public function switchProject(string $projectPath): void

// Search
public function searchText(): void
public function searchFiles(): void
public function searchSymbols(): void
public function clearSearch(): void
```

### UI Layout Structure

```
┌─────────────────────────────────────────────────────┐
│ Header: Project Explorer | Project: {name} [▼]      │
├──────────┬──────────────────────────────────────────┤
│ File     │ Search: [__________] [Text|Files|Symbols]│
│ Tree     │ ┌──────────────────────────────────────┐│
│          │ │ File Content / Search Results        ││
│ ▼ src/   │ │                                      ││
│   • foo  │ │ Syntax highlighted file content      ││
│   • bar  │ │ or                                   ││
│ ▼ tests/ │ │ Search results with line previews    ││
│   • test │ │                                      ││
│          │ │                                      ││
│          │ └──────────────────────────────────────┘│
├──────────┴──────────────────────────────────────────┤
│ Footer: {file_count} files | Line {line_num}        │
└─────────────────────────────────────────────────────┘
```

## Component 3: TUI Remote Control

### Livewire Component Properties

```php
class OpencodeRemote extends Component
{
    // TUI Connection
    public string $tuiServerUrl = 'http://127.0.0.1:64415';
    public bool $connected = false;

    // Remote Control
    public string $promptInput = '';
    public string $toastMessage = '';
    public string $selectedCommand = '';
    public array $availableCommands = [
        'agent_cycle' => 'Cycle through agents',
        'clear' => 'Clear screen',
        // ... etc
    ];

    // Status
    public ?array $tuiStatus = null;
    public bool $sending = false;
    public ?string $error = null;
}
```

### Key Methods

```php
// Connection
public function connect(): void
public function checkStatus(): void

// Prompt Management
public function submitPrompt(): void
public function appendPrompt(): void
public function clearPrompt(): void

// Actions
public function showToast(): void
public function executeCommand(string $command): void
public function openThemes(): void
public function openModels(): void
public function openHelp(): void
public function openSessions(): void
```

### UI Layout Structure

```
┌─────────────────────────────────────────────────────┐
│ Header: TUI Remote Control | Status: Connected ●    │
├─────────────────────────────────────────────────────┤
│ ┌─────────────────────────────────────────────────┐│
│ │ Prompt Input                                     ││
│ │ ┌─────────────────────────────────────────────┐ ││
│ │ │ Textarea for prompt                          │ ││
│ │ └─────────────────────────────────────────────┘ ││
│ │ [Submit] [Append] [Clear]                       ││
│ └─────────────────────────────────────────────────┘│
│ ┌─────────────────────────────────────────────────┐│
│ │ Quick Actions                                    ││
│ │ [Show Toast] [Open Themes] [Open Models]        ││
│ │ [Open Help] [Open Sessions]                     ││
│ └─────────────────────────────────────────────────┘│
│ ┌─────────────────────────────────────────────────┐│
│ │ Execute Command                                  ││
│ │ Select: [agent_cycle ▼]  [Execute]              ││
│ └─────────────────────────────────────────────────┘│
│ ┌─────────────────────────────────────────────────┐│
│ │ Toast Message                                    ││
│ │ ┌─────────────────────────────────────────────┐ ││
│ │ │ Message text...                              │ ││
│ │ └─────────────────────────────────────────────┘ ││
│ │ [Send Toast to TUI]                             ││
│ └─────────────────────────────────────────────────┘│
├─────────────────────────────────────────────────────┤
│ Footer: OpenCode TUI must be running for demo      │
└─────────────────────────────────────────────────────┘
```

## Component 4: Dashboard Landing Page

### Livewire Component Properties

```php
class OpencodeDashboard extends Component
{
    public array $components = [
        'chat' => [
            'title' => 'Chat & Sessions',
            'description' => 'Conversational AI with session branching',
            'endpoints' => 20,
            'icon' => 'chat-bubble-left-right',
            'route' => '/opencode-chat',
        ],
        // ... etc
    ];

    public array $stats = [
        'total_endpoints' => 51,
        'implemented' => 38,
        'coverage' => 75,
    ];
}
```

### UI Layout Structure

```
┌─────────────────────────────────────────────────────┐
│ OpenCode SDK Demo Suite                              │
│ Comprehensive demonstration of all SDK capabilities  │
├─────────────────────────────────────────────────────┤
│ ┌─────────────┐ ┌─────────────┐ ┌─────────────┐  │
│ │ 💬 Chat     │ │ 📁 Explorer │ │ 🎮 Remote   │  │
│ │ & Sessions  │ │ Code Intel  │ │ TUI Control │  │
│ │ 20 endpoints│ │ 9 endpoints │ │ 9 endpoints │  │
│ │ [Open →]    │ │ [Open →]    │ │ [Open →]    │  │
│ └─────────────┘ └─────────────┘ └─────────────┘  │
│                                                      │
│ SDK Coverage: 38/51 endpoints (75%)                 │
│ [■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■□□□□□□□□□□]         │
├─────────────────────────────────────────────────────┤
│ Footer: Built with Livewire & Flux UI Pro           │
└─────────────────────────────────────────────────────┘
```

## External Dependencies

### Syntax Highlighting
**Library**: highlight.js
**Installation**: `npm install highlight.js`
**Usage**: For file content syntax highlighting in Project Explorer
**Justification**: Industry-standard, supports 190+ languages, easy Livewire integration

### Session Tree Visualization
**Library**: vis-network (or D3.js)
**Installation**: `npm install vis-network`
**Usage**: Render session branching tree in Enhanced Chat
**Justification**: Specialized for graph/tree visualization, handles dynamic updates well

### Diff Viewer (Optional for v1)
**Library**: monaco-diff-editor (or simpler: diff2html)
**Installation**: `npm install diff2html`
**Usage**: Display code diffs for SessionDiff endpoint
**Justification**: Clean diff display without full Monaco overhead

## Performance Considerations

### Caching Strategy
- Cache session list for 5 seconds (reduce API calls)
- Cache file listings for 10 seconds
- Cache file contents for 30 seconds
- Invalidate cache on explicit user actions (refresh, new session, etc)

### Lazy Loading
- Load messages on-demand when switching sessions
- Load file contents only when opened
- Defer session tree rendering until expanded

### Polling Strategy
- Poll active sessions every 2 seconds for new messages
- Poll TUI status every 5 seconds when connected
- Stop polling when component not visible (Livewire lifecycle)

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

### Validation
Client-side validation for:
- Empty messages
- Invalid session IDs
- Empty file paths
- Invalid search queries

## Security Considerations

### Input Sanitization
- Escape all user input before display
- Validate session IDs match expected format
- Validate file paths don't escape project directory
- Sanitize shell commands (or disable for demo safety)

### CSRF Protection
- Use Livewire's built-in CSRF protection
- No additional tokens needed

### Demo Safety
- Consider disabling shell command execution in public demos
- Or restrict to safe commands (ls, pwd, echo, etc)
- Display warning before executing commands

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
