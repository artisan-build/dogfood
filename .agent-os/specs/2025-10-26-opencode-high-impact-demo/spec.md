# Spec Requirements Document

> Spec: OpenCode SDK High-Impact Demo Features
> Created: 2025-10-26
> Status: Planning

## Overview

Build three high-impact demo components for the OpenCode SDK that will serve as the centerpiece of a conference talk. These components showcase 38 of the 51 SDK endpoints (75%) and demonstrate the most compelling use cases: conversational AI with session branching, IDE-like code intelligence, and unique terminal UI remote control.

## User Stories

### Story 1: Conference Presenter Showcasing Session Management

As a conference presenter, I want to demonstrate OpenCode's session branching capabilities, so that attendees understand how AI conversations can explore multiple solution paths simultaneously without losing context.

**Workflow**: Presenter creates a new chat session asking "How should I implement authentication?". OpenCode responds with a solution. Presenter then forks the session at that message to explore an alternative approach ("What about using JWT instead?"). The interface shows a visual tree of both conversation branches, and presenter can switch between them to compare solutions. Attendees see that this is fundamentally different from linear chat interfaces.

**Problem Solved**: Traditional chat interfaces force users to choose one path and lose alternative explorations. This demonstrates non-destructive exploration of multiple solutions.

### Story 2: Developer Exploring Project Structure

As a developer integrating OpenCode, I want to see how the SDK provides IDE-like capabilities through a file browser and code search, so that I understand the full scope of what's possible beyond chat.

**Workflow**: Developer navigates to the project explorer, sees a familiar file tree interface, clicks through directories, opens files with syntax highlighting, and uses powerful search to find text, files, or symbols across the entire codebase. They realize OpenCode isn't just chat—it's comprehensive code intelligence.

**Problem Solved**: Demonstrates that OpenCode SDK provides programmatic access to the same powerful code navigation tools that developers expect from their IDE.

### Story 3: Integration Developer Controlling Terminal UI

As an integration developer, I want to see OpenCode's TUI (terminal user interface) being controlled remotely from a web interface, so that I realize I can build custom interfaces that orchestrate terminal-based AI tools.

**Workflow**: Developer sees a split screen with OpenCode running in a terminal on one side and a web interface on the other. The presenter clicks "Send Command" in the web UI and the terminal instantly responds. They trigger the theme picker remotely, show toast notifications appearing in the terminal, and execute commands. This demonstrates that OpenCode can be integrated into any workflow or interface.

**Problem Solved**: Shows the unique capability to build custom frontends for terminal-based AI tools, enabling integration into existing web apps, dashboards, or orchestration systems.

## Spec Scope

### Component 1: Enhanced Chat Interface

1. **Session List & Switcher** - Sidebar showing all sessions with create/switch/delete actions
2. **Session Forking** - Fork button on any message to create alternate exploration paths
3. **Session Tree Visualization** - Visual tree showing session relationships and branches
4. **Message History** - Full conversation history with message metadata
5. **Message Diffs** - View code changes that resulted from messages
6. **Session Actions Bar** - Abort, summarize, and manage session lifecycle
7. **Todo List Integration** - Display and track session todos
8. **Message Revert/Unrevert** - Undo message changes with visual feedback
9. **Session Sharing** - Share sessions with copy link functionality
10. **Shell Command Executor** - Run shell commands within session context
11. **Permission Modal** - Handle tool permission requests

**SDK Endpoints Used** (20):
- SessionCreate, SessionList, SessionGet, SessionUpdate, SessionDelete
- SessionInit, SessionAbort, SessionFork, SessionChildren
- SessionShare, SessionUnshare, SessionSummarize, SessionTodo
- SessionPrompt, SessionMessage, SessionMessages, SessionCommand
- SessionDiff, SessionRevert, SessionUnrevert
- PostSessionIdPermissionsPermissionId, SessionShell

### Component 2: Project Explorer

1. **File Tree Browser** - Hierarchical directory structure with expand/collapse
2. **File Content Viewer** - Syntax-highlighted file viewing with line numbers
3. **Project Switcher** - Dropdown to switch between multiple projects
4. **Path Breadcrumbs** - Navigate directory hierarchy with breadcrumb trail
5. **Text Search** - Full-text search across all project files with results preview
6. **File Finder** - Fuzzy search for files by name (Cmd+P style)
7. **Symbol Search** - Find functions, classes, and other code symbols
8. **File Status Indicators** - Visual indicators for git status (modified, added, etc)

**SDK Endpoints Used** (9):
- FileList, FileRead, FileStatus
- FindText, FindFiles, FindSymbols
- ProjectList, ProjectCurrent, PathGet

### Component 3: TUI Remote Control

1. **Remote Prompt Submission** - Send prompts to OpenCode TUI from web interface
2. **Prompt Management** - Append to or clear TUI prompt text
3. **Toast Notifications** - Trigger toast messages in terminal
4. **Command Execution** - Execute TUI commands (agent cycling, etc)
5. **Dialog Triggers** - Open theme picker, model selector, help, sessions dialog
6. **Live Status Display** - Show TUI state and active session
7. **Split-Screen Demo Mode** - Side-by-side terminal and web interface view

**SDK Endpoints Used** (9):
- TuiSubmitPrompt, TuiAppendPrompt, TuiClearPrompt
- TuiShowToast, TuiExecuteCommand
- TuiOpenThemes, TuiOpenModels, TuiOpenHelp, TuiOpenSessions

## Out of Scope

1. **User Authentication** - Demo runs in single-user mode without login
2. **Session Persistence** - Sessions stored in memory only, not database
3. **Collaborative Features** - Multi-user sessions and real-time collaboration
4. **Mobile Responsive Design** - Optimized for desktop/projector display only
5. **Configuration Management** - Uses fixed OpenCode server URL (saved for nice-to-have spec)
6. **Agent Management** - Agent discovery and configuration (saved for nice-to-have spec)
7. **Event Monitoring** - Real-time event streams and logging (saved for nice-to-have spec)
8. **Export Functionality** - Export sessions, transcripts, or code
9. **Offline Mode** - Requires running OpenCode server
10. **Accessibility Features** - Focus on visual demo for conference setting

## Expected Deliverable

### 1. Enhanced Chat Interface (`/opencode-chat`)
A polished Livewire component that demonstrates:
- Creating and managing multiple sessions
- Forking conversations at any point
- Visualizing session relationships as a tree
- Reverting and unreverting messages
- Viewing code diffs
- Managing session todos
- Running shell commands
- Handling permissions

**Browser Testable**: Can create session, send message, fork session, switch between sessions, view diffs, revert messages

### 2. Project Explorer (`/opencode-explorer`)
A file browser interface that demonstrates:
- Navigating project directory structure
- Opening and viewing files with syntax highlighting
- Searching for text, files, and symbols
- Switching between projects
- Following breadcrumb navigation

**Browser Testable**: Can browse files, open file to view contents, search for text and see results, find files by name, search for symbols

### 3. TUI Remote Control (`/opencode-remote`)
A remote control panel that demonstrates:
- Sending commands to terminal OpenCode instance
- Showing toast notifications in terminal
- Opening TUI dialogs remotely
- Appending/clearing prompt text
- Executing TUI commands

**Browser Testable**: Can send prompt to TUI, trigger toast, open theme picker, execute command (requires OpenCode TUI running in terminal for full demo)

### 4. Dashboard Landing Page (`/opencode`)
A main entry point that demonstrates:
- Navigation to all three components
- Overview of SDK capabilities
- Quick stats (endpoints covered, features available)
- Conference-ready branding

**Browser Testable**: Can navigate to each component from dashboard

## Spec Documentation

- Tasks: @.agent-os/specs/2025-10-26-opencode-high-impact-demo/tasks.md
- Technical Specification: @.agent-os/specs/2025-10-26-opencode-high-impact-demo/sub-specs/technical-spec.md
- Tests Specification: @.agent-os/specs/2025-10-26-opencode-high-impact-demo/sub-specs/tests.md
- UI/UX Specification: @.agent-os/specs/2025-10-26-opencode-high-impact-demo/sub-specs/ui-spec.md
