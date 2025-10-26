# Spec Requirements Document

> Spec: OpenCode SDK Nice-to-Have Demo Components
> Created: 2025-10-26
> Status: Planning

## Overview

Build four additional demo components for the OpenCode SDK that showcase the remaining 13 endpoints not covered by the high-impact components. These components demonstrate configuration management, agent extensibility, observability, and project initialization features. While less visually impressive than the high-impact components, they complete the SDK coverage and show important integration capabilities.

## User Stories

### Story 1: Developer Configuring Multi-Provider Setup

As a developer integrating OpenCode, I want to see how to configure multiple LLM providers (Anthropic, OpenAI, local models), so that I understand how to set up and switch between different AI backends.

**Workflow**: Developer opens the configuration manager, sees current config displayed as formatted JSON. They switch to the Providers tab and see all configured providers with status indicators (connected/disconnected). They add credentials for a new provider, test the connection, and see success confirmation. They can now switch between providers for different use cases.

**Problem Solved**: Demonstrates that OpenCode isn't locked to a single provider. Users can integrate multiple LLMs and choose the right tool for each task.

### Story 2: Integration Developer Discovering Available Tools

As an integration developer, I want to browse the complete catalog of tools and agents available in OpenCode, so that I understand what capabilities I can leverage programmatically.

**Workflow**: Developer navigates to the agents dashboard, sees grid of all available agents with descriptions. Clicking an agent shows its capabilities and configuration options. They switch to the Tools tab and see all registered tools with their JSON schemas. They search for specific functionality and find the tool they need. They can now use the SDK to invoke these tools programmatically.

**Problem Solved**: Shows the extensibility model and complete tool ecosystem, helping developers understand what OpenCode can do beyond basic chat.

### Story 3: DevOps Engineer Monitoring OpenCode Events

As a DevOps engineer, I want to see OpenCode's event stream and logs in real-time, so that I can debug issues and monitor system health in production.

**Workflow**: Engineer opens the events monitor and sees live event stream with filtering capabilities. They filter for error events and see detailed stack traces. They switch to the logs tab and write custom log entries to track specific operations. They check MCP server status to verify connections. This real-time visibility helps them diagnose and resolve issues quickly.

**Problem Solved**: Demonstrates observability features crucial for production deployments. Shows how to integrate OpenCode logging and events into existing monitoring systems.

### Story 4: New User Initializing Project with OpenCode

As a new OpenCode user, I want to initialize a project with proper configuration through a guided wizard, so that I don't miss important setup steps.

**Workflow**: User runs the initialization wizard, which analyzes their project structure, suggests configuration based on detected patterns, generates an AGENTS.md file with recommended settings, and completes setup. The wizard reduces friction for new users and ensures proper configuration from the start.

**Problem Solved**: Shows how to programmatically initialize OpenCode for new projects, useful for scaffold tools or IDE integrations.

## Spec Scope

### Component 1: Agent & Tool Dashboard

1. **Agent Discovery** - Grid view of all available agents with descriptions and capabilities
2. **Agent Details** - Detailed view showing agent configuration, tools it uses, and example usage
3. **Tool Registry** - Table of all registered tools with search/filter functionality
4. **Tool Schema Viewer** - Display JSON schemas for tool inputs/outputs with syntax highlighting
5. **Command Catalog** - List of available slash commands with syntax and examples
6. **Search & Filter** - Search across agents, tools, and commands by name or capability

**SDK Endpoints Used** (7):
- AppAgents, ToolList, ToolIds, CommandList

### Component 2: Configuration Manager

1. **Config Editor** - JSON/YAML editor for viewing and updating OpenCode configuration
2. **Provider Dashboard** - Card view of all LLM providers with status and details
3. **Provider Configuration** - Forms for adding/updating provider credentials and settings
4. **Credential Management** - Secure input for API keys with masked display
5. **Connection Testing** - Test button for each provider to verify credentials
6. **Config Export/Import** - Download config as JSON or upload existing configuration

**SDK Endpoints Used** (4):
- ConfigGet, ConfigUpdate, ConfigProviders, AuthSet

### Component 3: Events Monitor

1. **Live Event Stream** - Real-time display of events with SSE (Server-Sent Events)
2. **Event Filtering** - Filter events by type, source, severity, timestamp
3. **Event Details** - Expandable event cards showing full payload and metadata
4. **Log Viewer** - Display recent logs with severity levels and timestamps
5. **Custom Logging** - Input to write custom log entries to OpenCode server
6. **MCP Status Display** - Show MCP server health, connections, and protocol info
7. **Export Events** - Download event log as JSON for external analysis

**SDK Endpoints Used** (3):
- EventSubscribe, AppLog, McpStatus

### Component 4: Session Initialization Wizard

1. **Project Detection** - Analyze current directory and detect project type
2. **Configuration Wizard** - Multi-step form for setting up OpenCode
3. **AGENTS.md Preview** - Show generated AGENTS.md file before saving
4. **Customization Options** - Allow user to override detected settings
5. **Initialization Summary** - Display what was configured after completion
6. **Re-initialization** - Ability to run wizard again to update configuration

**SDK Endpoints Used** (1):
- SessionInit

## Out of Scope

1. **User Authentication** - Demo runs in single-user mode without login
2. **Provider Account Creation** - Links to provider websites, but doesn't create accounts
3. **MCP Server Management** - Shows status but doesn't start/stop MCP servers
4. **Event Alerting** - Real-time monitoring but no alert/notification system
5. **Config Version Control** - No git integration for config changes
6. **Agent Installation** - Discovery only, not installing new agents
7. **Tool Creation** - Shows existing tools but doesn't create custom tools
8. **Advanced Event Querying** - Basic filtering, not complex queries or aggregations
9. **Performance Metrics** - Status indicators but not detailed performance graphs
10. **Multi-Project Management** - Single project focus per component

## Expected Deliverable

### 1. Agent & Tool Dashboard (`/opencode-agents`)

A discovery interface that demonstrates:
- Browsing all available agents with capabilities
- Viewing complete tool registry with schemas
- Searching for specific functionality
- Understanding command syntax
- Exploring the extensibility model

**Browser Testable**: Can browse agents, view tool schemas, search for commands, click through to details

### 2. Configuration Manager (`/opencode-config`)

A settings interface that demonstrates:
- Viewing current OpenCode configuration
- Managing multiple LLM provider credentials
- Testing provider connections
- Updating configuration values
- Exporting/importing config files

**Browser Testable**: Can view config, add provider credentials (mocked), test connections, update settings

### 3. Events Monitor (`/opencode-monitor`)

A monitoring interface that demonstrates:
- Viewing live event stream
- Filtering events by criteria
- Writing custom log entries
- Checking MCP server status
- Exporting event data

**Browser Testable**: Can view events, filter by type, write logs, check MCP status (requires OpenCode running)

### 4. Session Initialization Wizard (`/opencode-init`)

A guided setup interface that demonstrates:
- Project structure detection
- Configuration recommendation
- AGENTS.md generation
- Step-by-step initialization
- Summary of configured settings

**Browser Testable**: Can run wizard, see project analysis, customize settings, view generated config

### 5. Updated Dashboard Landing Page (`/opencode`)

Enhanced dashboard showing all 7 components:
- Three high-impact cards (existing)
- Four nice-to-have cards (new)
- Updated coverage stats: 51/51 endpoints (100%)
- Navigation to all components

**Browser Testable**: Can navigate to any of the 7 components from dashboard

## Spec Documentation

- Technical Specification: @.agent-os/specs/2025-10-26-opencode-nice-to-have-demo/sub-specs/technical-spec.md
- Tests Specification: @.agent-os/specs/2025-10-26-opencode-nice-to-have-demo/sub-specs/tests.md
- UI/UX Specification: @.agent-os/specs/2025-10-26-opencode-nice-to-have-demo/sub-specs/ui-spec.md
