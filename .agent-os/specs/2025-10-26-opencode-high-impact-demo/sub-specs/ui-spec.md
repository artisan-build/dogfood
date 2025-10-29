# UI/UX Specification

This is the UI/UX specification for the spec detailed in @.agent-os/specs/2025-10-26-opencode-high-impact-demo/spec.md

> Created: 2025-10-26
> Version: 1.0.0

## Design Philosophy

### Conference-First Design

The demo interface is optimized for conference presentations:
- **Projector Resolution**: 1920x1080 minimum (scales well to 4K)
- **Viewing Distance**: Readable from 30+ feet away
- **Lighting Conditions**: High contrast for bright conference rooms
- **Demo Flow**: Clear visual hierarchy guides audience attention

### Visual Hierarchy

1. **Primary Actions**: Large, prominent buttons with clear labels
2. **Content**: Generous padding, clear separation between sections
3. **Metadata**: Subtle but readable secondary information
4. **Status**: Clear visual indicators for loading, success, error states

## Color Palette

### Base Colors (Projector-Safe)

```css
/* Primary Actions */
--blue-600: #2563EB;    /* Buttons, links, active states */
--blue-700: #1D4ED8;    /* Hover states */

/* Success States */
--green-600: #16A34A;   /* Success messages, checkmarks */
--green-700: #15803D;   /* Success hover */

/* Warning/Info */
--amber-500: #F59E0B;   /* Warnings, pending states */
--amber-600: #D97706;   /* Warning hover */

/* Error States */
--red-600: #DC2626;     /* Error messages, delete actions */
--red-700: #B91C1C;     /* Error hover */

/* Neutrals */
--gray-50: #F9FAFB;     /* Light background */
--gray-100: #F3F4F6;    /* Subtle backgrounds */
--gray-200: #E5E7EB;    /* Borders, dividers */
--gray-600: #4B5563;    /* Secondary text */
--gray-900: #111827;    /* Primary text */

/* Dark Mode */
--dark-bg: #111827;     /* Dark background */
--dark-card: #1F2937;   /* Dark card background */
--dark-text: #F9FAFB;   /* Dark mode text */
```

### Semantic Colors

- **Session Branch Colors**: Rotate through blue, green, purple, orange for visual distinction
- **Code Diff**: Green for additions, red for deletions, amber for modifications
- **Status Indicators**: Green dot (online), red dot (offline), amber dot (loading)

## Typography

### Font Stack

```css
/* Sans-serif for UI */
font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;

/* Monospace for code */
font-family: 'JetBrains Mono', 'Fira Code', 'Monaco', monospace;
```

### Type Scale (Conference-Optimized)

```css
/* Headings */
h1: 32px, font-weight: 700, line-height: 1.2
h2: 24px, font-weight: 600, line-height: 1.3
h3: 20px, font-weight: 600, line-height: 1.4
h4: 18px, font-weight: 500, line-height: 1.4

/* Body Text */
base: 16px, font-weight: 400, line-height: 1.5
large: 18px, font-weight: 400, line-height: 1.5

/* Small Text */
small: 14px, font-weight: 400, line-height: 1.5
xsmall: 12px, font-weight: 400, line-height: 1.4

/* Code */
code: 14px, font-weight: 400, line-height: 1.6
```

## Component 1: Enhanced Chat Interface

### Layout Structure

```
┌─────────────────────────────────────────────────────────────┐
│ Header (80px height)                                        │
│ ┌─────────────────────────────────────────────────────────┐│
│ │ OpenCode Chat                              [Actions ▼]  ││
│ └─────────────────────────────────────────────────────────┘│
├──────────────┬──────────────────────────────────────────────┤
│ Sidebar      │ Main Content Area                            │
│ (320px)      │ (Remaining width)                            │
│              │                                              │
│ Sessions     │ ┌──────────────────────────────────────────┐│
│ [+ New]      │ │ Session: "Auth Implementation"           ││
│              │ │ ┌──────────────────────────────────────┐ ││
│ ● Session 1  │ │ │ Messages (scroll area)               │ ││
│   ├─ Fork A  │ │ │                                      │ ││
│   └─ Fork B  │ │ │ [User message bubble]                │ ││
│ ○ Session 2  │ │ │ [Assistant message bubble]           │ ││
│              │ │ │                                      │ ││
│ Search [🔍]  │ │ └──────────────────────────────────────┘ ││
│              │ │ ┌──────────────────────────────────────┐ ││
│              │ │ │ Input Area (120px height)            │ ││
│              │ │ │ Type your message...                 │ ││
│              │ │ │ [Send] [Shell] [Todos] [Share]       │ ││
│              │ │ └──────────────────────────────────────┘ ││
│              │ └──────────────────────────────────────────┘│
└──────────────┴──────────────────────────────────────────────┘
```

### Session Sidebar

**Dimensions**: 320px width, full viewport height minus header

**Session Item**:
```
┌────────────────────────────────────┐
│ ● My Session Name                  │
│   Created 2h ago • 15 messages     │
│   [Fork] [Delete] [Rename]         │
└────────────────────────────────────┘
```

- **Active session**: Blue background, bold text, filled dot
- **Inactive session**: Gray text, outline dot
- **Hover state**: Light gray background, show action buttons
- **Nested forks**: 16px left indent, connecting lines to parent

**Session Tree Visualization**:
- Toggle button: "View Tree" shows modal with vis-network graph
- Nodes: Circular, labeled with session name
- Edges: Lines connecting parent to child forks
- Active session: Highlighted in blue, larger node
- Click node: Navigate to that session

### Message Bubbles

**User Message**:
```
┌──────────────────────────────────────────┐
│ You • 2 minutes ago                      │
│ ┌──────────────────────────────────────┐ │
│ │ How should I implement authentication?│ │
│ └──────────────────────────────────────┘ │
│ [Fork at this message] [Revert]          │
└──────────────────────────────────────────┘
```
- Right-aligned, blue background (#EFF6FF)
- Max width: 70% of container
- Border radius: 12px
- Padding: 16px
- Actions appear on hover

**Assistant Message**:
```
┌──────────────────────────────────────────┐
│ Assistant • 1 minute ago                 │
│ ┌──────────────────────────────────────┐ │
│ │ I recommend using Laravel Sanctum...  │ │
│ │                                       │ │
│ │ ```php                                │ │
│ │ // Code with syntax highlighting      │ │
│ │ ```                                   │ │
│ └──────────────────────────────────────┘ │
│ [Fork] [View Diff] [Copy Code]           │
└──────────────────────────────────────────┘
```
- Left-aligned, gray background (#F3F4F6)
- Code blocks: Dark background, syntax highlighted
- Actions appear on hover
- Diff button shows if message made file changes

**Reverted Message**:
- Opacity: 60%
- Strikethrough text
- Badge: "Reverted" in red
- Button: "Unrevert" to restore

### Input Area

**Textarea**:
- Height: Auto-expand from 60px to 200px
- Placeholder: "Type your message... (Shift+Enter for new line)"
- Border: 2px solid gray-200, focus: blue-600
- Font size: 16px

**Action Buttons Row**:
```
[Send Message]  [Execute Shell]  [View Todos]  [Share Session]
  Primary         Secondary        Secondary      Secondary
```

- Send: Blue background, white text, disabled when empty
- Others: Gray background, hover to blue
- Icons + text labels for clarity

### Session Actions Bar

Dropdown menu in header showing:
```
┌───────────────────────────┐
│ ✓ Summarize Session       │
│ ✓ Share Session           │
│ ✓ Fork Session            │
│ ✓ Rename Session          │
│ ───────────────────────   │
│ ⏸ Abort (if running)      │
│ ✓ View Tree               │
│ ✓ Export as Markdown      │
│ ───────────────────────   │
│ 🗑 Delete Session         │
└───────────────────────────┘
```

### Todos Panel

Slides in from right side (400px width):
```
┌─────────────────────────────────┐
│ Session Todos           [Close] │
│ ───────────────────────────────│
│ ☐ Implement user model          │
│ ☑ Create migration              │
│ ☐ Add authentication routes     │
│ ☐ Test login flow               │
└─────────────────────────────────┘
```

- Checkbox: Large (24px), easy to click
- Completed: Green checkmark, strikethrough text
- Pending: Empty checkbox
- Click to toggle

### Diff Viewer Modal

```
┌─────────────────────────────────────────────┐
│ Code Changes from Message            [Close]│
│ ─────────────────────────────────────────── │
│ app/Models/User.php                          │
│ ┌─────────────────────────────────────────┐ │
│ │ - 10: protected $fillable = [];         │ │ (red bg)
│ │ + 10: protected $fillable = ['name'];   │ │ (green bg)
│ │   11:                                   │ │
│ │ + 12: public function teams() {         │ │ (green bg)
│ └─────────────────────────────────────────┘ │
└─────────────────────────────────────────────┘
```

- Line numbers on left
- Green background for additions
- Red background for deletions
- Syntax highlighting maintained
- Side-by-side or unified view toggle

## Component 2: Project Explorer

### Layout Structure

```
┌─────────────────────────────────────────────────────────────┐
│ Project Explorer                          Project: [Kibble ▼]│
├──────────────┬──────────────────────────────────────────────┤
│ File Tree    │ Content / Search Results                     │
│ (280px)      │                                              │
│              │ ┌──────────────────────────────────────────┐│
│ ▼ src/       │ │ Search: [________________] [🔍]          ││
│   ▼ Models/  │ │ [Text] [Files] [Symbols]                 ││
│     • User   │ │                                          ││
│     • Team   │ └──────────────────────────────────────────┘│
│   ▶ Services/│ ┌──────────────────────────────────────────┐│
│ ▼ tests/     │ │ File: src/Models/User.php                ││
│   • UserTest │ │ ┌──────────────────────────────────────┐ ││
│ • README.md  │ │ │ 1  <?php                             │ ││
│              │ │ │ 2                                    │ ││
│ [Collapse]   │ │ │ 3  namespace App\Models;             │ ││
│              │ │ │ 4                                    │ ││
│              │ │ │ 5  class User extends Model          │ ││
│              │ │ └──────────────────────────────────────┘ ││
│              │ └──────────────────────────────────────────┘│
└──────────────┴──────────────────────────────────────────────┘
```

### File Tree

**Directory Item**:
```
▼ src/                       [Folder icon, expanded]
  ▶ Controllers/             [Folder icon, collapsed]
  • User.php                 [File icon]
  • Team.php                 [File icon]
```

- Folder: Bold text, arrow indicates expand/collapse
- File: Regular text, file extension icon
- Indentation: 16px per level
- Hover: Light gray background
- Active file: Blue background, bold text

**File Icons by Extension**:
- `.php`: PHP logo or bracket icon
- `.js`: JavaScript logo
- `.blade.php`: Blade icon
- `.md`: Document icon
- `.json`: Brackets icon

**Git Status Badges**:
- Modified: Orange dot
- Added: Green plus icon
- Deleted: Red minus icon
- Appears next to filename

### Content Area

**Breadcrumb Navigation**:
```
Home / src / Models / User.php
```
- Click any segment to navigate
- Separator: / character
- Last item (current): Bold, not clickable

**Code Viewer**:
- Line numbers: Right-aligned, gray
- Current line: Highlighted background
- Syntax highlighting: highlight.js with GitHub theme
- Font: JetBrains Mono, 14px
- Line height: 1.6
- Scrollbar: Always visible when needed

### Search Interface

**Search Bar**:
```
┌────────────────────────────────────────┐
│ Search...                         [🔍] │
└────────────────────────────────────────┘
[Text] [Files] [Symbols]
```

- Tab selection: Active tab has blue underline
- Search updates as you type (debounced 300ms)
- Clear button appears when text entered

**Search Results - Text**:
```
┌────────────────────────────────────────┐
│ src/Models/User.php                    │
│   Line 23: public function login()     │
│   Line 45: // Login validation         │
│ ──────────────────────────────────────│
│ src/Controllers/AuthController.php     │
│   Line 12: public function login(...)  │
└────────────────────────────────────────┘
```

- File path: Bold, blue (clickable)
- Line number: Gray, small
- Preview text: Matched term highlighted in yellow
- Click to open file at that line

**Search Results - Files**:
```
┌────────────────────────────────────────┐
│ 📄 UserController.php                  │
│    src/Http/Controllers/               │
│ 📄 UserTest.php                        │
│    tests/Feature/                      │
│ 📄 user.blade.php                      │
│    resources/views/                    │
└────────────────────────────────────────┘
```

- Fuzzy matching with highlighted characters
- Path shown below filename
- Icon indicates file type

**Search Results - Symbols**:
```
┌────────────────────────────────────────┐
│ class User                             │
│   📁 src/Models/User.php:5             │
│ function getUserById                   │
│   ƒ src/Services/UserService.php:23    │
│ const API_VERSION                      │
│   ⚙ src/Config/api.php:10              │
└────────────────────────────────────────┘
```

- Symbol type icon (class, function, const)
- Symbol name: Bold
- Location: File path + line number
- Color coding: Classes (blue), functions (purple), constants (orange)

### Project Switcher

Dropdown in header:
```
┌───────────────────────────┐
│ ● Kibble (current)        │
│ ○ OpenCode SDK            │
│ ○ Example Project         │
└───────────────────────────┘
```

- Current project: Filled dot, bold
- Others: Empty dot
- Click to switch
- File tree refreshes on switch

## Component 3: TUI Remote Control

### Layout Structure

```
┌─────────────────────────────────────────────────────────────┐
│ TUI Remote Control                    Status: Connected ●   │
├─────────────────────────────────────────────────────────────┤
│ ┌─────────────────────────────────────────────────────────┐ │
│ │ Prompt Input                                            │ │
│ │ ┌─────────────────────────────────────────────────────┐ │ │
│ │ │ Type your prompt for OpenCode TUI...                │ │ │
│ │ │                                                       │ │ │
│ │ └─────────────────────────────────────────────────────┘ │ │
│ │ [Submit Prompt]  [Append to TUI]  [Clear TUI Prompt]   │ │
│ └─────────────────────────────────────────────────────────┘ │
│                                                             │
│ ┌─────────────────────────────────────────────────────────┐ │
│ │ Quick Actions                                           │ │
│ │ [📢 Show Toast]  [🎨 Open Themes]  [🤖 Open Models]    │ │
│ │ [❓ Open Help]   [📋 Open Sessions]                     │ │
│ └─────────────────────────────────────────────────────────┘ │
│                                                             │
│ ┌─────────────────────────────────────────────────────────┐ │
│ │ Execute Command                                         │ │
│ │ Command: [agent_cycle          ▼]  [Execute]           │ │
│ └─────────────────────────────────────────────────────────┘ │
│                                                             │
│ ┌─────────────────────────────────────────────────────────┐ │
│ │ Toast Message                                           │ │
│ │ ┌─────────────────────────────────────────────────────┐ │ │
│ │ │ Enter message to display in TUI...                  │ │ │
│ │ └─────────────────────────────────────────────────────┘ │ │
│ │ [Send Toast to TUI]                                     │ │
│ └─────────────────────────────────────────────────────────┘ │
└─────────────────────────────────────────────────────────────┘
```

### Connection Status

Header indicator:
```
Status: Connected ●    (green dot)
Status: Connecting ●   (amber dot, pulsing)
Status: Disconnected ● (red dot)
```

- Auto-check every 5 seconds
- Clicking status triggers manual reconnect
- Error message if TUI not running

### Prompt Input Section

**Card Layout**:
- White background, subtle shadow
- Padding: 24px
- Border radius: 8px
- Title: "Prompt Input" (18px, bold)

**Textarea**:
- Height: 120px
- Placeholder: "Type your prompt for OpenCode TUI..."
- Font: 16px
- Border: 2px gray, focus: blue

**Button Group**:
```
[Submit Prompt]        [Append to TUI]        [Clear TUI Prompt]
  Blue, primary          Gray, secondary         Red, secondary
```

- Equal width buttons
- Gap: 12px between
- Icon + text for each

### Quick Actions Section

**Card Layout**: Same as Prompt Input

**Button Grid**:
```
[📢 Show Toast]    [🎨 Open Themes]    [🤖 Open Models]
[❓ Open Help]     [📋 Open Sessions]
```

- 3 columns on desktop
- 2 columns on smaller screens
- Icon + label
- Blue background on hover
- Brief loading state after click

### Command Execution Section

**Dropdown Select**:
```
Command: [agent_cycle                     ▼]
```

Options:
- agent_cycle - Cycle through agents
- clear - Clear screen
- exit - Exit TUI
- help - Show help
- themes - Open themes
- models - Open models
- sessions - Open sessions

**Execute Button**:
- Primary blue
- Full width on mobile
- Shows success toast after execution

### Toast Message Section

**Textarea**:
- Height: 80px
- Placeholder: "Enter message to display in TUI..."
- Character counter: "0/200" (updates as typed)

**Send Button**:
- Primary blue
- Disabled when empty
- Success feedback after send

## Component 4: Dashboard Landing Page

### Layout Structure

```
┌─────────────────────────────────────────────────────────────┐
│              OpenCode SDK Demo Suite                        │
│        Comprehensive demonstration of SDK capabilities      │
├─────────────────────────────────────────────────────────────┤
│                                                             │
│  ┌─────────────────┐  ┌─────────────────┐  ┌─────────────┐│
│  │ 💬              │  │ 📁              │  │ 🎮          ││
│  │ Chat & Sessions │  │ Project Explorer│  │ TUI Remote  ││
│  │                 │  │                 │  │ Control     ││
│  │ Conversational  │  │ IDE-like code   │  │ Terminal UI ││
│  │ AI with session │  │ intelligence    │  │ remote      ││
│  │ branching       │  │ and navigation  │  │ control     ││
│  │                 │  │                 │  │             ││
│  │ 20 endpoints    │  │ 9 endpoints     │  │ 9 endpoints ││
│  │ [Open →]        │  │ [Open →]        │  │ [Open →]    ││
│  └─────────────────┘  └─────────────────┘  └─────────────┘│
│                                                             │
│  SDK Coverage: 38/51 endpoints (75%)                        │
│  [████████████████████████████████░░░░░░░░░░░░]            │
│                                                             │
│  ┌─────────────────────────────────────────────────────┐   │
│  │ About This Demo                                     │   │
│  │ This application demonstrates 38 of the 51 OpenCode │   │
│  │ SDK endpoints through three interactive components. │   │
│  │ Click any card above to explore specific features.  │   │
│  └─────────────────────────────────────────────────────┘   │
└─────────────────────────────────────────────────────────────┘
```

### Component Cards

**Card Dimensions**:
- Width: 320px
- Height: 280px
- Gap: 32px between cards
- Responsive: Stack on mobile

**Card Design**:
```
┌─────────────────────────┐
│ 💬 (icon, 48px)         │
│                         │
│ Chat & Sessions         │ (24px, bold)
│                         │
│ Conversational AI with  │ (16px, gray)
│ session branching and   │
│ history management      │
│                         │
│ 20 endpoints            │ (14px, badge)
│                         │
│ [Open →]                │ (button)
└─────────────────────────┘
```

- Hover: Lift effect (shadow increases)
- Click: Navigate to component
- Icon: Large, centered at top
- Description: 2-3 lines, gray text
- Badge: Pill shape, light blue background

### Progress Bar

**Coverage Indicator**:
```
SDK Coverage: 38/51 endpoints (75%)
[████████████████████████████████░░░░░░░░░░░░]
```

- Gradient: Blue to green
- Height: 24px
- Border radius: 12px
- Percentage label above bar
- Animation: Fill on page load

### Info Section

**Card Style**:
- Light gray background
- Padding: 24px
- Border radius: 8px
- Centered text
- Max width: 600px

## Responsive Behavior

### Breakpoints

- **Desktop**: 1024px+
  - 3-column layout for cards
  - Sidebar visible
  - Full width content area

- **Tablet**: 768px - 1023px
  - 2-column layout for cards
  - Collapsible sidebar
  - Adjusted padding

- **Mobile**: < 768px
  - 1-column layout
  - Hidden sidebar (hamburger menu)
  - Stack all UI elements
  - Touch-optimized buttons (min 44px height)

### Conference Display

Optimized for 1920x1080 presentation:
- All text readable from 30 feet
- High contrast ratios
- No horizontal scrolling
- Generous click targets
- Clear visual hierarchy

## Animation & Transitions

### Micro-interactions

- **Button Hover**: Scale 1.02, shadow increase (150ms)
- **Card Hover**: Lift effect, shadow grow (200ms)
- **Input Focus**: Border color change, shadow (150ms)
- **Loading States**: Pulsing opacity (1000ms loop)
- **Success/Error**: Slide in from top (300ms)

### Page Transitions

- **Session Switch**: Fade out old, fade in new (200ms)
- **Modal Open**: Fade in backdrop + slide up content (250ms)
- **Sidebar Toggle**: Slide in/out (300ms)
- **File Open**: Fade content (150ms)

### Loading States

**Skeleton Screens**:
- Use for initial page load
- Gray blocks pulse gently
- Match final layout structure

**Spinners**:
- Use for short operations (< 3s)
- Blue circular spinner
- Center of container

**Progress Bars**:
- Use for known-duration operations
- Show percentage if available
- Indeterminate for unknown duration

## Accessibility Considerations

### Color Contrast

- Text on background: Minimum 4.5:1 ratio
- Large text (18px+): Minimum 3:1 ratio
- Interactive elements: Clear focus indicators
- Error messages: Don't rely on color alone

### Keyboard Navigation

- All interactive elements: Tab-accessible
- Logical tab order
- Visible focus states (blue outline)
- Escape key: Close modals
- Enter key: Submit forms, trigger actions

### Screen Readers

- Semantic HTML: Proper heading hierarchy
- ARIA labels: For icon-only buttons
- Live regions: For dynamic content updates
- Skip links: Jump to main content

### Touch Targets

- Minimum size: 44x44px
- Adequate spacing: 8px between targets
- Visual feedback: On tap/click
- Swipe gestures: Optional, not required

## Error States

### Connection Errors

```
┌─────────────────────────────────────┐
│ ⚠️ Connection Failed                │
│                                     │
│ Could not connect to OpenCode       │
│ server at http://127.0.0.1:64415    │
│                                     │
│ Make sure OpenCode is running:      │
│ $ opencode                          │
│                                     │
│ [Retry Connection]                  │
└─────────────────────────────────────┘
```

- Clear error message
- Actionable suggestion
- Retry button
- Dismissible

### Operation Errors

Toast notification:
```
┌───────────────────────────────┐
│ ❌ Operation Failed           │
│ Could not send message        │
│ [Dismiss]                     │
└───────────────────────────────┘
```

- Top-right corner
- Auto-dismiss after 5 seconds
- Red background
- Click to dismiss

### Empty States

```
┌─────────────────────────────────────┐
│          📭                         │
│                                     │
│    No sessions yet                  │
│                                     │
│ Create your first session to        │
│ start chatting with OpenCode        │
│                                     │
│ [Create Session]                    │
└─────────────────────────────────────┘
```

- Icon + message
- Explanation
- Call to action button
- Centered in container

## Success States

### Toast Notifications

```
┌───────────────────────────────┐
│ ✓ Success!                    │
│ Session created successfully  │
└───────────────────────────────┘
```

- Green background
- Top-right corner
- Auto-dismiss after 3 seconds
- Checkmark icon

### Inline Success

Within forms:
```
✓ Message sent successfully
```

- Green text
- Checkmark icon
- Below relevant field

## Loading States

### Skeleton Screen Example

```
┌─────────────────────────────────────┐
│ ▭▭▭▭▭▭▭▭▭▭ ▭▭▭▭▭                  │
│ ▭▭▭▭▭▭▭▭▭▭▭▭▭▭▭▭▭▭▭▭▭▭▭▭▭▭▭▭▭    │
│ ▭▭▭▭▭▭▭▭▭▭▭▭▭▭▭▭                  │
│                                     │
│ ▭▭▭▭▭▭▭▭▭▭ ▭▭▭▭▭                  │
│ ▭▭▭▭▭▭▭▭▭▭▭▭▭▭▭▭▭▭▭▭▭▭▭▭▭▭▭▭▭    │
└─────────────────────────────────────┘
```

- Pulse animation
- Gray blocks
- Match final layout

### Button Loading State

```
[Sending... ⟳]
```

- Spinner icon
- Disabled state
- Text changes to action in progress

## Dark Mode Support

All components support dark mode toggle:

**Color Adjustments**:
- Background: #111827
- Card background: #1F2937
- Text: #F9FAFB
- Borders: #374151
- Primary: Brighter blue (#3B82F6)

**Syntax Highlighting**: Dark theme for code blocks

**Images/Icons**: Invert or use dark variants

## Demo-Specific Features

### Split-Screen Mode

For TUI Remote demonstration:
```
┌─────────────────┬─────────────────┐
│ Web Interface   │ Terminal TUI    │
│ (Left half)     │ (Right half)    │
└─────────────────┴─────────────────┘
```

- 50/50 split
- Web sends commands
- Terminal shows response
- Synchronized for demo

### Presenter Mode

Toggle for larger text during presentation:
- 1.25x font scale
- Increased padding
- Reduced sidebar width
- Focus on main content

### Demo Reset

Quick button to reset demo state:
- Clear all sessions
- Reset to initial state
- Preload sample sessions
- Hidden in corner (admin only)
