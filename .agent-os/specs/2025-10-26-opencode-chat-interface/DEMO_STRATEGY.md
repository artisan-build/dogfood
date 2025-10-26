# OpenCode SDK Conference Demo Strategy

> A comprehensive plan for demonstrating all 51 OpenCode SDK endpoints

## Executive Summary

The OpenCode SDK provides 51 endpoints across 13 functional categories. This document outlines a multi-component demo application strategy that showcases every capability of the SDK in an engaging, conference-friendly format.

---

## Demo Component Architecture

### Component 1: Enhanced Chat Interface (Current - Expand)
**Location**: `/opencode-chat`
**Primary Focus**: Session Management & Messaging

#### Currently Implemented
- ✅ Session creation
- ✅ Sending prompts
- ✅ Receiving responses

#### Enhancements to Add

**Session Management Panel** (Left Sidebar)
- [ ] **SessionList** - Display all sessions in a sidebar
- [ ] **SessionGet** - Show session details when selected
- [ ] **SessionCreate** - Add "New Session" button
- [ ] **SessionFork** - Fork session at any message point
- [ ] **SessionChildren** - Show session family tree/branching
- [ ] **SessionDelete** - Delete button with confirmation
- [ ] **SessionShare/Unshare** - Share toggle with copy link
- [ ] **SessionUpdate** - Rename session inline

**Message History Enhancements**
- [ ] **SessionMessages** - Load full message history on session select
- [ ] **SessionMessage** - Click message to see details/metadata
- [ ] **SessionDiff** - Show code diffs for messages that changed files
- [ ] **SessionRevert/Unrevert** - Undo/redo buttons on messages
- [ ] **SessionTodo** - Display session todos as a checklist

**Session Actions Bar**
- [ ] **SessionAbort** - Stop button during long-running responses
- [ ] **SessionSummarize** - Summarize button generates session summary
- [ ] **SessionCommand** - Command palette (Cmd+K style)
- [ ] **SessionShell** - Embedded terminal for shell commands

**Permission Handling**
- [ ] **PostSessionIdPermissionsPermissionId** - Permission modal for tool use approvals

---

### Component 2: Project Explorer
**Location**: `/opencode-explorer`
**Primary Focus**: File Operations, Code Search, Project Management

#### File Browser Panel
- [ ] **FileList** - Tree view of project files/directories
- [ ] **FileRead** - Click file to view contents with syntax highlighting
- [ ] **FileStatus** - Git status badges on files (modified, added, etc)
- [ ] **ProjectList** - Project switcher dropdown
- [ ] **ProjectCurrent** - Display current project info
- [ ] **PathGet** - Breadcrumb navigation

#### Search Panel
- [ ] **FindText** - Full-text search with live results
- [ ] **FindFiles** - Fuzzy file finder (Cmd+P style)
- [ ] **FindSymbols** - Symbol search (classes, functions, etc)

**UI Design**: Split-pane with file tree on left, file content on right, search bar at top

**Demo Value**: Shows how to build a lightweight IDE-like file browser powered by OpenCode

---

### Component 3: Agent & Tool Dashboard
**Location**: `/opencode-agents`
**Primary Focus**: Tools, Agents, Commands

#### Agents Panel
- [ ] **AppAgents** - Grid of available agents with descriptions
- [ ] Click agent to see capabilities and configuration

#### Tools Panel
- [ ] **ToolList** - Table of tools for selected provider/model
- [ ] **ToolIds** - Complete tool registry with filters
- [ ] Show tool JSON schemas in expandable details

#### Commands Panel
- [ ] **CommandList** - List of slash commands
- [ ] Search and filter commands
- [ ] Show command syntax and examples

**UI Design**: Tab interface with Agents, Tools, and Commands sections

**Demo Value**: Explains the extensibility model and available integrations

---

### Component 4: Configuration Manager
**Location**: `/opencode-config`
**Primary Focus**: Configuration, Authentication, Providers

#### Configuration Editor
- [ ] **ConfigGet** - Load and display current config as JSON/YAML
- [ ] **ConfigUpdate** - Live editor with save functionality
- [ ] Form builder for common config options

#### Provider Management
- [ ] **ConfigProviders** - Card view of all LLM providers
- [ ] Show provider status, models, and rate limits
- [ ] Provider-specific configuration forms

#### Authentication
- [ ] **AuthSet** - Secure credential input for API keys
- [ ] Masked input fields with "test connection" button

**UI Design**: Settings-page style with nested sections

**Demo Value**: Shows configuration flexibility and multi-provider support

---

### Component 5: MCP & Events Monitor
**Location**: `/opencode-monitor`
**Primary Focus**: Events, Logging, MCP Status

#### Event Stream
- [ ] **EventSubscribe** - Live SSE event stream display
- [ ] Filter events by type
- [ ] Expandable event details with JSON viewer

#### Logging Panel
- [ ] **AppLog** - Write custom logs to OpenCode server
- [ ] Show recent logs from OpenCode
- [ ] Log level filtering

#### MCP Status
- [ ] **McpStatus** - Display MCP server health and stats
- [ ] Server connection indicators
- [ ] Protocol version info

**UI Design**: Developer console style with real-time updates

**Demo Value**: Debugging and observability features

---

### Component 6: TUI Remote Control
**Location**: `/opencode-remote`
**Primary Focus**: TUI Operations

> **Note**: This demonstrates controlling the OpenCode TUI from a web interface

#### Remote TUI Panel
- [ ] **TuiSubmitPrompt** - Send prompts to TUI remotely
- [ ] **TuiAppendPrompt** - Append to current TUI prompt
- [ ] **TuiClearPrompt** - Clear TUI prompt button

#### TUI Actions
- [ ] **TuiShowToast** - Send notification to TUI
- [ ] **TuiExecuteCommand** - Button grid for TUI commands
- [ ] **TuiOpenThemes** - Trigger theme picker
- [ ] **TuiOpenModels** - Trigger model selector
- [ ] **TuiOpenHelp** - Trigger help dialog
- [ ] **TuiOpenSessions** - Trigger session picker

**UI Design**: Remote control interface mimicking TUI layout

**Demo Value**: Shows API control of terminal UI - unique feature!

---

### Component 7: Session Initialization Wizard
**Location**: `/opencode-init`
**Primary Focus**: SessionInit

#### Wizard Flow
- [ ] **SessionInit** - Step-by-step setup wizard
  1. Select project directory
  2. Analyze project structure
  3. Review generated AGENTS.md
  4. Customize agent configuration
  5. Complete initialization

**UI Design**: Multi-step wizard with progress indicator

**Demo Value**: Onboarding experience for new projects

---

## Implementation Priority

### Phase 1: Enhanced Chat (Week 1)
**High Impact, Core Functionality**
- Session list/switcher
- Session forking/branching
- Message history with diffs
- Session todos

### Phase 2: Project Explorer (Week 2)
**Developer Tools Foundation**
- File browser
- Code search (text, files, symbols)
- Project management

### Phase 3: Configuration & Agents (Week 3)
**Platform Capabilities**
- Config editor
- Provider management
- Agent/tool discovery

### Phase 4: Advanced Features (Week 4)
**Unique Differentiators**
- TUI remote control
- Event monitoring
- Session initialization wizard

---

## Conference Demo Flow (Suggested)

### Act 1: Chat & Sessions (5 minutes)
1. Create new session
2. Send programming question
3. Fork session to explore alternative approach
4. Show session branching tree
5. Revert a message, show diff

### Act 2: Project Exploration (4 minutes)
1. Switch to file explorer
2. Browse project files
3. Search for symbols
4. Find text across codebase
5. Open file to show syntax highlighting

### Act 3: Extensibility (3 minutes)
1. Show agent dashboard
2. Browse available tools
3. Demonstrate custom command

### Act 4: Remote Control (3 minutes)
1. Open TUI remote
2. Send command to terminal OpenCode instance
3. Show toast notification appearing
4. Open theme picker remotely

### Act 5: Configuration (2 minutes)
1. Show provider management
2. Switch between LLM providers
3. Update configuration live

### Finale: Live Coding (3 minutes)
1. Return to chat
2. Ask OpenCode to implement a feature
3. Show file changes in explorer
4. Run tests via shell command
5. Demonstrate full development cycle

**Total: ~20 minutes with buffer**

---

## Technical Implementation Notes

### Shared Infrastructure

```php
// Base OpenCode client service
class OpencodeService
{
    public function __construct(
        protected string $baseUrl = 'http://127.0.0.1:64415'
    ) {}

    public function client(): OpenCode
    {
        return new OpenCode(baseUrl: $this->baseUrl);
    }
}
```

### Livewire Component Structure

```
packages/opencode-client/
├── src/
│   ├── Livewire/
│   │   ├── OpencodeChat.php (enhanced)
│   │   ├── OpencodeExplorer.php (new)
│   │   ├── OpencodeAgents.php (new)
│   │   ├── OpencodeConfig.php (new)
│   │   ├── OpencodeMonitor.php (new)
│   │   ├── OpencodeRemote.php (new)
│   │   └── OpencodeInit.php (new)
│   └── Services/
│       └── OpencodeService.php (new)
└── resources/views/
    └── livewire/
        ├── opencode-chat.blade.php (enhanced)
        ├── opencode-explorer.blade.php (new)
        ├── opencode-agents.blade.php (new)
        ├── opencode-config.blade.php (new)
        ├── opencode-monitor.blade.php (new)
        ├── opencode-remote.blade.php (new)
        └── opencode-init.blade.php (new)
```

### Navigation

Create a main dashboard at `/opencode` with cards linking to each component:

```
┌─────────────────────────────────────┐
│     OpenCode SDK Demo Suite         │
├─────────────────────────────────────┤
│  💬 Chat & Sessions                 │
│  📁 Project Explorer                │
│  🤖 Agents & Tools                  │
│  ⚙️  Configuration                   │
│  📊 Events & Monitoring             │
│  🎮 TUI Remote Control              │
│  🚀 Session Initialization          │
└─────────────────────────────────────┘
```

---

## Testing Strategy

### Integration Tests for Each Component

```php
test('can create and switch sessions', function () {
    Livewire::test(OpencodeChat::class)
        ->call('connect')
        ->assertSet('sessionId', fn($id) => $id !== null)
        ->call('createNewSession')
        ->call('switchSession', $previousId)
        ->assertSet('sessionId', $previousId);
});
```

### SDK Coverage Matrix

Create a checklist to ensure every endpoint is demonstrated:

- [ ] 13/13 Session Management endpoints
- [ ] 7/7 Session Messages endpoints
- [ ] 2/2 Session Shell & Permissions endpoints
- [ ] 3/3 File Operations endpoints
- [ ] 3/3 Code Search endpoints
- [ ] 3/3 Project Management endpoints
- [ ] 9/9 TUI Operations endpoints
- [ ] 3/3 Configuration endpoints
- [ ] 3/3 Tools & Agents endpoints
- [ ] 1/1 Commands endpoints
- [ ] 2/2 Events & Logging endpoints
- [ ] 1/1 MCP endpoints
- [ ] 1/1 Authentication endpoints

**Target: 51/51 endpoints demonstrated**

---

## Additional Demo Enhancements

### Visual Polish
- Syntax highlighting for code (highlight.js or Shiki)
- Diff viewer for SessionDiff (Monaco Diff Editor)
- Markdown rendering for messages
- Loading states with skeleton screens
- Error toasts with retry buttons

### Developer Experience
- Keyboard shortcuts (Cmd+K, Cmd+P, etc)
- Dark/light mode toggle
- Responsive design for projector demos
- Export session as markdown
- Share session with QR code

### "Wow" Factors for Conference
1. **Live session branching visualization** - Animated tree showing parallel exploration paths
2. **Real-time file watcher** - Show files changing as OpenCode modifies them
3. **Token usage meter** - Live cost tracking per session
4. **Speed comparison** - Multiple providers answering same question side-by-side
5. **Agent collaboration** - Show multiple agents working on same session

---

## Resources Needed

### Design Assets
- [ ] Logo and branding for demo app
- [ ] Icon set for each component
- [ ] Color scheme that works on projectors
- [ ] Screenshots for README

### Content
- [ ] Sample projects for demos
- [ ] Pre-configured prompts/questions
- [ ] Video recording of demo flow
- [ ] Blog post explaining architecture

### Infrastructure
- [ ] OpenCode server running on demo machine
- [ ] Fallback pre-recorded demo video
- [ ] QR code to live demo URL
- [ ] GitHub repo for demo code

---

## Success Metrics

### For Conference Attendees
- ✅ Clear understanding of SDK capabilities
- ✅ Confidence they could integrate OpenCode
- ✅ Excitement about specific features
- ✅ Questions about advanced use cases

### For SDK Adoption
- GitHub stars on opencode-sdk repo
- npm/composer downloads
- Community-built integrations
- Conference talk feedback scores

---

## Next Steps

1. **Review this strategy** - Get feedback on approach
2. **Pick starting component** - Likely enhanced chat or explorer
3. **Create spec** - Detailed spec for first component
4. **Build iteratively** - One component at a time
5. **Test on projector** - Ensure visibility and performance
6. **Record backup video** - In case of demo gremlins
7. **Practice flow** - Rehearse the 20-minute narrative

---

## Open Questions

1. Should we build all 7 components or focus on 3-4 most impressive?
2. Do we need offline/demo mode for unreliable conference wifi?
3. Should we package this as a standalone demo app or keep in Kibble?
4. Do we want to showcase error handling and edge cases?
5. Should we include a "build along" tutorial in the presentation?

---

**Document Version**: 1.0
**Last Updated**: 2025-10-26
**Status**: Planning Phase
