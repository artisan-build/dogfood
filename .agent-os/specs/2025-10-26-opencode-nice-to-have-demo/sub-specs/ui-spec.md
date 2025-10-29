# UI/UX Specification

This is the UI/UX specification for the spec detailed in @.agent-os/specs/2025-10-26-opencode-nice-to-have-demo/spec.md

> Created: 2025-10-26
> Version: 1.0.0

## Design Philosophy

### Consistent with High-Impact Components

Nice-to-have components share the same design system as high-impact components:
- Same color palette (projector-safe)
- Same typography (Inter + JetBrains Mono)
- Same component library (Flux UI Pro)
- Same animation principles
- Same responsive breakpoints

### Developer-Focused Design

These components target developers integrating OpenCode:
- Technical information presented clearly
- JSON/code displayed prominently
- Documentation-like feel
- Less visual flair, more substance
- Emphasis on information density

## Color Palette

Uses the same palette defined in high-impact ui-spec.md:
- Primary: Blue (#2563EB)
- Success: Green (#16A34A)
- Warning: Amber (#F59E0B)
- Error: Red (#DC2626)
- Neutrals: Gray scale

Additional semantic colors for nice-to-have:
- **Config Valid**: Green indicator
- **Config Invalid**: Red indicator
- **Provider Active**: Green dot
- **Provider Inactive**: Gray dot
- **Event Severity**: Info (blue), Warning (amber), Error (red), Debug (gray)

## Typography

Same type scale as high-impact components, with emphasis on:
- **Code Blocks**: JetBrains Mono, 14px, line-height 1.6
- **JSON Display**: Syntax highlighting with highlight.js
- **Technical Labels**: Monospace for IDs, API keys, versions

## Component 1: Agent & Tool Dashboard

### Layout Structure

```
┌─────────────────────────────────────────────────────────────┐
│ Agents & Tools                                              │
├─────────────────────────────────────────────────────────────┤
│ [Agents] [Tools] [Commands]                                 │
├─────────────────────────────────────────────────────────────┤
│ Search: [________________________________] [🔍]             │
├──────────────┬──────────────────────────────────────────────┤
│ Agent List   │ Agent Details                                │
│ (Optional)   │ (Main Content)                               │
└──────────────┴──────────────────────────────────────────────┘
```

### Agents Tab

**Agent Grid Layout**:
```
┌───────────────┐  ┌───────────────┐  ┌───────────────┐
│ 🤖            │  │ 🤖            │  │ 🤖            │
│ Database      │  │ Frontend      │  │ Security      │
│ Agent         │  │ Agent         │  │ Agent         │
│               │  │               │  │               │
│ Handles DB    │  │ React/Vue     │  │ Auth & crypto │
│ schemas and   │  │ components    │  │ best practice │
│ migrations    │  │ and styling   │  │ checks        │
│               │  │               │  │               │
│ [View Details]│  │ [View Details]│  │ [View Details]│
└───────────────┘  └───────────────┘  └───────────────┘
```

- Card width: 280px
- Card height: Auto (min 240px)
- Gap: 24px
- Icon: 48px at top
- Title: 18px, bold
- Description: 14px, gray, 2-3 lines
- Button: Full width, blue

**Agent Details Panel** (when selected):
```
┌─────────────────────────────────────────────────────┐
│ 🤖 Database Agent                          [Close]  │
├─────────────────────────────────────────────────────┤
│ Description                                         │
│ Specialized in database schema design, migrations,  │
│ and query optimization for relational databases.    │
│                                                     │
│ Capabilities                                        │
│ • Schema design                                     │
│ • Migration generation                              │
│ • Query optimization                                │
│ • Index recommendations                             │
│                                                     │
│ Tools Used                                          │
│ • database_query                                    │
│ • schema_analyzer                                   │
│ • migration_generator                               │
│                                                     │
│ Configuration                                       │
│ Provider: anthropic                                 │
│ Model: claude-3-5-sonnet-20241022                   │
│ Temperature: 0.7                                    │
└─────────────────────────────────────────────────────┘
```

- Slides in from right (400px wide) or modal on mobile
- Close button top-right
- Sections clearly separated
- Bullet lists for capabilities and tools
- Code formatting for technical values

### Tools Tab

**Tool Table**:
```
┌────────────────────────────────────────────────────────────┐
│ Tool Name          Provider    Model             Actions   │
├────────────────────────────────────────────────────────────┤
│ file_read          anthropic   claude-sonnet    [Schema]  │
│ database_query     anthropic   claude-sonnet    [Schema]  │
│ web_search         openai      gpt-4            [Schema]  │
│ code_execute       local       codellama        [Schema]  │
│ image_analyze      anthropic   claude-sonnet    [Schema]  │
└────────────────────────────────────────────────────────────┘

Filter: Provider [All ▼]  Model [All ▼]
```

- Sortable columns
- Compact spacing (12px row padding)
- Monospace for tool names
- Filter dropdowns above table
- Schema button opens modal

**Tool Schema Modal**:
```
┌─────────────────────────────────────────────────────────┐
│ Tool Schema: file_read                         [Close] │
├─────────────────────────────────────────────────────────┤
│ Input Schema                                            │
│ ┌─────────────────────────────────────────────────────┐│
│ │ {                                                   ││
│ │   "type": "object",                                 ││
│ │   "properties": {                                   ││
│ │     "path": {                                       ││
│ │       "type": "string",                             ││
│ │       "description": "File path to read"            ││
│ │     }                                               ││
│ │   },                                                ││
│ │   "required": ["path"]                              ││
│ │ }                                                   ││
│ └─────────────────────────────────────────────────────┘│
│                                                         │
│ Output Schema                                           │
│ ┌─────────────────────────────────────────────────────┐│
│ │ {                                                   ││
│ │   "type": "object",                                 ││
│ │   "properties": {                                   ││
│ │     "content": {                                    ││
│ │       "type": "string",                             ││
│ │       "description": "File contents"                ││
│ │     }                                               ││
│ │   }                                                 ││
│ │ }                                                   ││
│ └─────────────────────────────────────────────────────┘│
│                                                         │
│ [Copy Schema] [Close]                                   │
└─────────────────────────────────────────────────────────┘
```

- Modal: 700px wide
- Syntax highlighting with highlight.js
- Line numbers optional
- Copy button copies to clipboard
- Scrollable if schema is large

### Commands Tab

**Command List**:
```
┌─────────────────────────────────────────────────────────┐
│ /analyze                                               │
│ Analyze project structure and suggest improvements     │
│                                                         │
│ Syntax: /analyze [path] [--verbose]                    │
│                                                         │
│ Examples:                                               │
│ • /analyze src/                                         │
│ • /analyze --verbose                                    │
│ • /analyze app/Models --verbose                         │
│                                                         │
│ [Copy Command]                                          │
├─────────────────────────────────────────────────────────┤
│ /refactor                                              │
│ Suggest refactoring opportunities in code              │
│                                                         │
│ Syntax: /refactor <file>                                │
│                                                         │
│ Examples:                                               │
│ • /refactor src/Service.php                             │
│ • /refactor app/Models/User.php                         │
│                                                         │
│ [Copy Command]                                          │
└─────────────────────────────────────────────────────────┘
```

- Card layout for each command
- Padding: 20px
- Gap: 16px between cards
- Command name: Bold, 18px, monospace
- Description: Regular, 14px
- Syntax: Monospace, gray background
- Examples: Bulleted list, monospace
- Copy button per command

## Component 2: Configuration Manager

### Layout Structure

```
┌─────────────────────────────────────────────────────────────┐
│ Configuration Manager                                       │
├─────────────────────────────────────────────────────────────┤
│ [Configuration] [Providers]                                 │
├─────────────────────────────────────────────────────────────┤
│ Content Area (varies by tab)                                │
└─────────────────────────────────────────────────────────────┘
```

### Configuration Tab

**Config Viewer/Editor**:
```
┌─────────────────────────────────────────────────────────┐
│ Configuration                 [Edit] [Export] [Import] │
├─────────────────────────────────────────────────────────┤
│ ┌─────────────────────────────────────────────────────┐│
│ │   1  {                                              ││
│ │   2    "version": "1.0",                            ││
│ │   3    "providers": {                               ││
│ │   4      "anthropic": {                             ││
│ │   5        "enabled": true,                         ││
│ │   6        "api_key": "sk-ant-***",                 ││
│ │   7        "models": [                              ││
│ │   8          "claude-3-5-sonnet-20241022"           ││
│ │   9        ]                                        ││
│ │  10      },                                         ││
│ │  11      "openai": {                                ││
│ │  12        "enabled": false,                        ││
│ │  13        "api_key": null,                         ││
│ │  14        "models": ["gpt-4"]                      ││
│ │  15      }                                          ││
│ │  16    },                                           ││
│ │  17    "defaults": {                                ││
│ │  18      "provider": "anthropic",                   ││
│ │  19      "model": "claude-3-5-sonnet-20241022"      ││
│ │  20    }                                            ││
│ │  21  }                                              ││
│ └─────────────────────────────────────────────────────┘│
│                                                         │
│ [Save Changes] [Reset]                                  │
└─────────────────────────────────────────────────────────┘
```

**View Mode**:
- Read-only display
- Syntax highlighting (JSON)
- Line numbers on left
- Masked sensitive values (api_key shows ***)

**Edit Mode**:
- JSON editor (jsoneditor library)
- Live syntax validation
- Error highlighting
- Auto-formatting
- Save/Reset buttons enabled

**Action Buttons**:
- Edit: Toggles edit mode
- Export: Downloads config.json
- Import: File picker → validates → updates
- Save: Only in edit mode, disabled if invalid
- Reset: Reverts to last saved

### Providers Tab

**Provider Cards Grid**:
```
┌───────────────────┐  ┌───────────────────┐  ┌──────────────┐
│ Anthropic         │  │ OpenAI            │  │ Local Models │
│ ● Connected       │  │ ○ Not Configured  │  │ ○ Disabled   │
│                   │  │                   │  │              │
│ Models: 3         │  │ Models: 5         │  │ Models: 2    │
│ Last used: 2m ago │  │ Last used: Never  │  │ Last: 1h ago │
│                   │  │                   │  │              │
│ [Configure]       │  │ [Configure]       │  │ [Configure]  │
└───────────────────┘  └───────────────────┘  └──────────────┘
```

- Card width: 300px
- Card height: 180px
- Gap: 20px
- Status dot: 12px, top-left
- Green = connected, gray = not configured
- Hover: Subtle lift effect
- Click card or button to configure

**Provider Configuration Panel**:
```
┌─────────────────────────────────────────────────────────┐
│ Configure: Anthropic                           [Close] │
├─────────────────────────────────────────────────────────┤
│ API Key                                                 │
│ ┌─────────────────────────────────────────────────────┐│
│ │ sk-ant-********************************           👁 ││
│ └─────────────────────────────────────────────────────┘│
│                                                         │
│ Available Models                                        │
│ ☑ claude-3-5-sonnet-20241022                           │
│ ☑ claude-3-opus-20240229                               │
│ ☑ claude-3-haiku-20240307                              │
│                                                         │
│ Default Model                                           │
│ [claude-3-5-sonnet-20241022 ▼]                         │
│                                                         │
│ Advanced Settings                                       │
│ Temperature: [0.7    ] (0-1)                            │
│ Max Tokens:  [4096   ]                                  │
│                                                         │
│ [Test Connection] [Save Configuration]                  │
└─────────────────────────────────────────────────────────┘
```

- Panel: Slides in from right (500px) or modal
- API Key: Masked by default, eye icon toggles visibility
- Models: Checkboxes for enabled models
- Dropdowns: Standard Flux UI select
- Test Connection: Shows loading → success/error toast
- Save: Updates config, closes panel

**Connection Test States**:
```
[Testing Connection...  ⟳]  (Blue, disabled during test)
[Test Connection]            (Default state)

After test:
✓ Connection Successful      (Green toast, 3s)
✗ Connection Failed          (Red toast with error message)
```

## Component 3: Events Monitor

### Layout Structure

```
┌─────────────────────────────────────────────────────────────┐
│ Events Monitor                    [Pause] [Clear] [Export] │
├─────────────────────────────────────────────────────────────┤
│ [Events] [Logs] [MCP Status]                                │
├─────────────────────────────────────────────────────────────┤
│ Filters: Type [All ▼]  Severity [All ▼]  [Auto-scroll ☑]  │
├─────────────────────────────────────────────────────────────┤
│ Event Stream (scrollable)                                   │
└─────────────────────────────────────────────────────────────┘
```

### Events Tab

**Event Stream Display**:
```
┌─────────────────────────────────────────────────────────┐
│ 12:34:56  INFO   session.message                       │
│   Session ses_abc123 received new message              │
│   [Expand ▼]                                            │
├─────────────────────────────────────────────────────────┤
│ 12:34:55  ERROR  api.request_failed                    │
│   Connection timeout to provider "anthropic"           │
│   [Expand ▼]                                            │
├─────────────────────────────────────────────────────────┤
│ 12:34:54  DEBUG  file.read                             │
│   Read file: src/Models/User.php (234 bytes)           │
│   [Expand ▼]                                            │
└─────────────────────────────────────────────────────────┘
```

**Event Card**:
- Border-left: 4px, color-coded by severity
  - INFO: Blue
  - WARNING: Amber
  - ERROR: Red
  - DEBUG: Gray
- Timestamp: Monospace, 12px, gray
- Severity: Badge, uppercase, colored background
- Type: Monospace, 14px, bold
- Summary: 14px, truncated to one line
- Expand button: Chevron icon

**Expanded Event**:
```
┌─────────────────────────────────────────────────────────┐
│ 12:34:55  ERROR  api.request_failed                    │
│   Connection timeout to provider "anthropic"           │
│   [Collapse ▲]                                          │
│                                                         │
│   Full Details:                                         │
│   ┌───────────────────────────────────────────────────┐│
│   │ {                                                 ││
│   │   "event_id": "evt_xyz789",                       ││
│   │   "timestamp": "2025-10-26T12:34:55Z",            ││
│   │   "type": "api.request_failed",                   ││
│   │   "severity": "error",                            ││
│   │   "source": "opencode-core",                      ││
│   │   "data": {                                       ││
│   │     "provider": "anthropic",                      ││
│   │     "endpoint": "/v1/messages",                   ││
│   │     "error": "Connection timeout after 30s"       ││
│   │   }                                               ││
│   │ }                                                 ││
│   └───────────────────────────────────────────────────┘│
│                                                         │
│   [Copy JSON]                                           │
└─────────────────────────────────────────────────────────┘
```

- JSON payload: Syntax highlighted, scrollable
- Copy button: Copies full JSON to clipboard
- Collapse button: Returns to summary view

**Control Buttons (Header)**:
```
[Pause]    → Stops new events from appearing
[Resume]   → Resumes event stream (shown when paused)
[Clear]    → Clears all events (with confirmation)
[Export]   → Downloads events as JSON
```

**Filter Dropdowns**:
- Type: Lists all observed event types
- Severity: All, Info, Warning, Error, Debug
- Auto-scroll checkbox: Scrolls to newest event

### Logs Tab

**Log Writer**:
```
┌─────────────────────────────────────────────────────────┐
│ Write Custom Log Entry                                 │
├─────────────────────────────────────────────────────────┤
│ Message                                                 │
│ ┌─────────────────────────────────────────────────────┐│
│ │ Enter log message...                                ││
│ │                                                     ││
│ └─────────────────────────────────────────────────────┘│
│                                                         │
│ Level: [info ▼]                                         │
│                                                         │
│ [Write Log]                                             │
└─────────────────────────────────────────────────────────┘
```

- Textarea: 120px height, auto-expand
- Level dropdown: info, warning, error, debug
- Write button: Blue, full width on mobile

**Recent Logs Display**:
```
┌─────────────────────────────────────────────────────────┐
│ Recent Logs                                             │
├─────────────────────────────────────────────────────────┤
│ 12:35:01  INFO   Custom log from web UI                │
│ 12:34:58  DEBUG  Session initialized                   │
│ 12:34:55  ERROR  Connection timeout                    │
│ 12:34:52  INFO   Application started                   │
└─────────────────────────────────────────────────────────┘
```

- Same styling as event stream
- Limited to last 50 logs
- Color-coded by severity

### MCP Status Tab

**Status Dashboard**:
```
┌─────────────────────────────────────────────────────────┐
│ MCP Server Status                         [Refresh]    │
├─────────────────────────────────────────────────────────┤
│ ┌─────────────────┐  ┌─────────────────┐              │
│ │ Status          │  │ Protocol        │              │
│ │ ✓ Running       │  │ Version: 1.0    │              │
│ └─────────────────┘  └─────────────────┘              │
│                                                         │
│ ┌─────────────────┐  ┌─────────────────┐              │
│ │ Connections     │  │ Uptime          │              │
│ │ 3 active        │  │ 2h 34m 12s      │              │
│ └─────────────────┘  └─────────────────┘              │
│                                                         │
│ Active Connections:                                     │
│ • Session ses_abc123 → anthropic                        │
│ • Session ses_def456 → openai                           │
│ • Session ses_ghi789 → local                            │
└─────────────────────────────────────────────────────────┘
```

- Card grid: 2 columns
- Cards: White background, subtle border
- Status: Green checkmark if running, red X if down
- Connections: List of active sessions
- Refresh button: Manual refresh, shows loading state

## Component 4: Session Initialization Wizard

### Layout Structure

```
┌─────────────────────────────────────────────────────────────┐
│ Session Initialization Wizard                               │
├─────────────────────────────────────────────────────────────┤
│ Progress: [████████░░░░░░░░░░] Step 2 of 5                  │
├─────────────────────────────────────────────────────────────┤
│ Step Content (varies by step)                               │
├─────────────────────────────────────────────────────────────┤
│                              [Previous] [Next / Complete]   │
└─────────────────────────────────────────────────────────────┘
```

### Progress Bar

```
Progress: [████████░░░░░░░░░░] Step 2 of 5
```

- Filled: Blue gradient
- Empty: Light gray
- Height: 8px
- Border radius: 4px
- Text above: "Step X of Y"

### Step 1: Project Selection

```
┌─────────────────────────────────────────────────────────┐
│ Step 1: Select Project Directory                       │
├─────────────────────────────────────────────────────────┤
│ Project Path                                            │
│ ┌─────────────────────────────────────────────────────┐│
│ │ /Users/username/projects/my-app              [📁]  ││
│ └─────────────────────────────────────────────────────┘│
│                                                         │
│ [Analyze Project]                                       │
│                                                         │
│ The wizard will analyze your project structure and     │
│ detect the framework, language, and recommended        │
│ OpenCode configuration.                                 │
└─────────────────────────────────────────────────────────┘
```

- Path input: Full width, browse icon
- Browse button: Opens file picker
- Analyze button: Primary blue, disabled until path valid
- Help text: Gray, explains what happens next

### Step 2: Review Detection

```
┌─────────────────────────────────────────────────────────┐
│ Step 2: Review Detected Configuration                  │
├─────────────────────────────────────────────────────────┤
│ ✓ Project Type: Laravel Application                    │
│ ✓ Framework: Laravel 11.0                               │
│ ✓ Language: PHP 8.3                                     │
│ ✓ Database: SQLite                                      │
│ ✓ Package Manager: Composer                             │
│                                                         │
│ ☐ Override detected settings                            │
│                                                         │
│ All detected settings look correct? Click Next to       │
│ continue with these settings.                           │
└─────────────────────────────────────────────────────────┘
```

- Each item: Green checkmark, bold label, value
- Override checkbox: Shows override form when checked
- Help text: Explains option to proceed or customize

### Step 3: Customize Configuration

```
┌─────────────────────────────────────────────────────────┐
│ Step 3: Customize Configuration                        │
├─────────────────────────────────────────────────────────┤
│ Provider                                                │
│ [anthropic ▼]                                           │
│                                                         │
│ Model                                                   │
│ [claude-3-5-sonnet-20241022 ▼]                         │
│                                                         │
│ Agent Role                                              │
│ [code_assistant ▼]                                      │
│                                                         │
│ Additional Settings                                     │
│ Temperature: [0.7    ] (0-1)                            │
│ Max Tokens:  [4096   ]                                  │
└─────────────────────────────────────────────────────────┘
```

- Dropdowns: Standard Flux UI select
- Number inputs: With validation and range hints
- All fields optional (defaults provided)

### Step 4: Preview AGENTS.md

```
┌─────────────────────────────────────────────────────────┐
│ Step 4: Preview AGENTS.md                              │
├─────────────────────────────────────────────────────────┤
│ The following file will be created in your project:    │
│                                                         │
│ ┌─────────────────────────────────────────────────────┐│
│ │ # Agent Configuration                               ││
│ │                                                     ││
│ │ ## Provider                                         ││
│ │ anthropic                                           ││
│ │                                                     ││
│ │ ## Model                                            ││
│ │ claude-3-5-sonnet-20241022                          ││
│ │                                                     ││
│ │ ## Role                                             ││
│ │ code_assistant                                      ││
│ │                                                     ││
│ │ ## Settings                                         ││
│ │ - Temperature: 0.7                                  ││
│ │ - Max Tokens: 4096                                  ││
│ └─────────────────────────────────────────────────────┘│
│                                                         │
│ [Edit Manually] [Reset to Generated]                   │
└─────────────────────────────────────────────────────────┘
```

- Preview: Read-only or editable textarea
- Markdown formatting maintained
- Edit button: Enables editing
- Reset button: Reverts to generated version

### Step 5: Complete Initialization

```
┌─────────────────────────────────────────────────────────┐
│ Step 5: Initialize Session                             │
├─────────────────────────────────────────────────────────┤
│ Ready to initialize OpenCode for your project!         │
│                                                         │
│ The following will be created:                         │
│ ✓ AGENTS.md in project root                            │
│ ✓ .opencode/ configuration directory                   │
│ ✓ Initial session                                      │
│                                                         │
│ [Initialize]                                            │
│                                                         │
│ After initialization:                                   │
│                                                         │
│ ✓ Configuration saved                                  │
│ ✓ AGENTS.md created                                    │
│ ✓ Session initialized: ses_abc123                      │
│                                                         │
│ [Complete] [Start Over]                                 │
└─────────────────────────────────────────────────────────┘
```

**Before Init**:
- Shows checklist of what will be created
- Initialize button: Large, primary blue

**After Init**:
- Shows checkmarks for completed items
- Displays session ID
- Complete button: Returns to dashboard
- Start Over: Resets wizard

### Navigation Buttons

```
[← Previous]                                      [Next →]
```

- Previous: Gray, left-aligned, disabled on step 1
- Next: Blue, right-aligned, disabled if step incomplete
- Last step shows "Complete" instead of "Next"

## Responsive Behavior

### Breakpoints

Same as high-impact components:
- Desktop: 1024px+ (full layout)
- Tablet: 768-1023px (adjusted padding, collapsible sidebars)
- Mobile: < 768px (stacked layout, touch-optimized)

### Component-Specific Responsive

**Agent Cards**: 3 columns → 2 columns → 1 column
**Tool Table**: Scrollable horizontally on mobile
**Config Editor**: Full width on mobile, smaller font
**Event Stream**: Full width, compact padding
**Wizard**: Full screen on mobile

## Animation & Transitions

Same principles as high-impact components:
- Button hover: 150ms scale + shadow
- Tab switch: 200ms fade
- Modal open: 250ms slide + fade
- Loading states: Gentle pulse

## Error States

### Connection Error
```
┌─────────────────────────────────────────────┐
│ ⚠️ Connection Failed                        │
│                                             │
│ Could not connect to OpenCode server        │
│ at http://127.0.0.1:64415                   │
│                                             │
│ [Retry]                                     │
└─────────────────────────────────────────────┘
```

### Validation Error
```
┌─────────────────────────────────────────────┐
│ ❌ Invalid Configuration                    │
│                                             │
│ Line 7: Unexpected token '}'                │
│                                             │
│ [Dismiss]                                   │
└─────────────────────────────────────────────┘
```

### Empty State
```
┌─────────────────────────────────────────────┐
│          📋                                 │
│                                             │
│    No events yet                            │
│                                             │
│ Events will appear here when OpenCode       │
│ performs operations.                        │
└─────────────────────────────────────────────┘
```

## Success States

### Toast Notification
```
┌──────────────────────────────┐
│ ✓ Configuration Saved        │
└──────────────────────────────┘
```

- Top-right corner
- Green background
- Auto-dismiss 3s

### Inline Success
```
✓ Connection successful
```

- Green text + checkmark
- Below relevant field

## Loading States

### Skeleton Screen
```
┌─────────────────────────────────┐
│ ▭▭▭▭▭▭▭▭▭▭▭▭▭▭▭▭▭▭▭▭▭▭▭▭▭▭▭  │
│ ▭▭▭▭▭▭▭▭▭▭                      │
│                                 │
│ ▭▭▭▭▭▭▭▭▭▭▭▭▭▭▭▭▭▭▭▭▭▭▭▭▭▭▭  │
│ ▭▭▭▭▭▭▭▭▭▭                      │
└─────────────────────────────────┘
```

### Button Loading
```
[Initializing... ⟳]
```

- Spinner icon
- Disabled state
- Text changes to action

## Accessibility

Same standards as high-impact components:
- 4.5:1 contrast ratio
- Keyboard navigation
- ARIA labels
- Semantic HTML
- Focus indicators
