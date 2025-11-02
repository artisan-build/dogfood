# Spec Requirements Document

> Spec: Agent OS Web Viewer
> Created: 2025-11-01
> Status: Planning

## Overview

Add a web interface to the agent-os-installer package that enables non-developer stakeholders to browse, read, and search Agent OS documentation (.agent-os folder contents) through a user-friendly interface powered by Livewire and Flux UI.

## User Stories

### Non-Developer Stakeholder Reviews Spec

As a product manager or business stakeholder, I want to review spec documentation through a web interface, so that I can understand planned features and provide feedback without needing to navigate markdown files in a code editor.

**Workflow:** Stakeholder navigates to /agent-os route in their browser, sees a clean interface showing product mission, roadmap, and all specs. They click into a specific spec to read the requirements, technical details, and task breakdown. They can search for specific terms across all documentation.

### Developer Shares Spec for Review

As a developer, I want to send a link to non-technical stakeholders for spec review, so that they can review and approve feature plans before implementation begins.

**Workflow:** Developer completes spec planning, copies the /agent-os/specs/spec-name URL, and shares it via email or Slack. Stakeholder clicks the link, authenticates if required, and reviews the spec in a formatted, readable view with syntax highlighting for code blocks and proper markdown rendering.

### Team Member Searches Agent OS Documentation

As a team member, I want to search across all Agent OS documentation, so that I can quickly find information about decisions, technical requirements, or specific features without manually browsing multiple files.

**Workflow:** Team member opens the Agent OS web viewer, uses the search box to query "authentication" or "database schema", and sees results from specs, decisions.md, mission.md, and other relevant files with highlighted matches and file context.

## Spec Scope

1. **Browse Interface** - Livewire component with left sidebar navigation showing product docs and chronologically ordered specs, with main content area displaying selected document or unified spec view
2. **Unified Spec View** - Concatenate all spec-related files (spec.md, sub-specs/*.md, tasks.md) into single page view with appropriate section headers, displaying spec.md first, then sub-specs, then tasks at bottom
3. **Markdown Rendering** - Parse and display markdown files with syntax highlighting for code blocks, proper heading hierarchy, and support for Agent OS @ reference links
4. **File-Based Search** - Real-time search across all .agent-os markdown files using PHP string matching with result highlighting and file context snippets
5. **Customizable Access Control** - Configurable middleware/gate that package consumers can customize to restrict access based on authentication, roles, or environment
6. **Configurable Routes and Sources** - Base route (/agent-os by default) is configurable via environment variable, README.md displays as default index page, and additional markdown directories can be merged into the viewer
7. **Responsive Flux UI** - Mobile-friendly interface using Flux UI (free/base) components only, without requiring flux-pro dependency, that matches the aesthetic of host Laravel applications

## Out of Scope

- Database indexing or caching (file-based only for MVP)
- Write capabilities (comments, edits, suggestions)
- Real-time collaboration features
- Vector embeddings or semantic search
- Version history or diff viewing
- Export to PDF or other formats
- Multi-language support

## Expected Deliverable

1. Navigating to configurable route (default /agent-os) displays concatenated view of .agent-os/product folder contents (mission.md, roadmap.md, tech-stack.md, decisions.md) as the index page with sidebar navigation and search box that works without authentication in local dev environment
2. Search functionality returns relevant results from all markdown files in .agent-os folder and any additional configured directories with highlighted matches and can handle queries with 2+ word phrases
3. Package can be installed in any Laravel 11+ project and route prefix can be customized via AGENT_OS_ROUTE_PREFIX environment variable (e.g., /documentation, /docs, /agent-os)

## Spec Documentation

- Tasks: @.agent-os/specs/2025-11-01-agent-os-web-viewer/tasks.md
- Technical Specification: @.agent-os/specs/2025-11-01-agent-os-web-viewer/sub-specs/technical-spec.md
- Tests Specification: @.agent-os/specs/2025-11-01-agent-os-web-viewer/sub-specs/tests.md
