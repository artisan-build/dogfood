# Technical Specification

This is the technical specification for the spec detailed in @.agent-os/specs/2025-11-01-agent-os-web-viewer/spec.md

> Created: 2025-11-01
> Version: 1.0.0

## Technical Requirements

### Package Architecture

- Add new Livewire components to agent-os-installer package under `src/Livewire/` directory
- Components should be namespaced as `ArtisanBuild\AgentOsInstaller\Livewire\`
- Views should be published to `resources/views/vendor/agent-os-installer/`
- Routes should be registered in service provider with customizable prefix via environment variable:
  - Environment variable: `AGENT_OS_ROUTE_PREFIX` (default: `agent-os`)
  - Example routes: `/agent-os`, `/documentation`, `/docs`
  - Index route should display concatenated view of `.agent-os/product/` folder by default

### Navigation Structure

**Sidebar Navigation (Left):**
- Flat navigation structure with chronological ordering for specs
- Navigation groups:
  1. **Product** (top, special item for concatenated product folder view)
     - When clicked: displays concatenated view of mission.md, roadmap.md, tech-stack.md, decisions.md
     - Default/index view when first loading the viewer
  2. **Specs** (middle, reverse chronological by date)
     - 2025-11-01: Agent OS Web Viewer
     - 2025-10-15: Previous Spec Name
     - etc.
  3. **Additional Directories** (bottom, if configured)
     - User Documentation
     - etc.
  4. **README** (always at bottom)
     - Links to project's README.md file

**Main Content Area:**
- Single unified view for selected item
- For Product: concatenated view of all files in .agent-os/product/ folder
- For specs: concatenated view of all spec-related files
- For README/other docs: single file view

### Product Folder Concatenation Logic

When "Product" is selected from sidebar (or when loading index route):

1. **Read all product files:**
   - `.agent-os/product/mission.md`
   - `.agent-os/product/roadmap.md`
   - `.agent-os/product/tech-stack.md`
   - `.agent-os/product/decisions.md`

2. **Concatenation order:**
   ```
   [Full content of mission.md]

   ---

   # Roadmap
   [Full content of roadmap.md]

   ---

   # Tech Stack
   [Full content of tech-stack.md]

   ---

   # Decisions
   [Full content of decisions.md]
   ```

3. **Section handling:**
   - Keep mission.md heading as-is (it's the first section)
   - Strip top-level `#` heading from other files
   - Use filename to generate section header (e.g., "Roadmap" from `roadmap.md`)
   - Add horizontal rules (`---`) between sections

4. **Performance:**
   - Cache concatenated product content
   - Use Livewire's `#[Computed]` attribute

### Unified Spec View Logic

When a spec is selected from sidebar navigation:

1. **Read all spec-related files:**
   - Main spec file: `spec.md`
   - All files in `sub-specs/` directory (sorted alphabetically)
   - Tasks file: `tasks.md` (if exists)

2. **Concatenation order:**
   ```
   [Full content of spec.md]

   ---

   # Technical Specification
   [Full content of sub-specs/technical-spec.md]

   ---

   # Database Schema
   [Full content of sub-specs/database-schema.md]

   ---

   # API Specification
   [Full content of sub-specs/api-spec.md]

   ---

   # Tests Specification
   [Full content of sub-specs/tests.md]

   ---

   # Tasks
   [Full content of tasks.md]
   ```

3. **Section headers:**
   - Strip the top-level `# heading` from each sub-file
   - Use filename to generate section header (e.g., "Technical Specification" from `technical-spec.md`)
   - Add horizontal rules (`---`) between sections

4. **Performance:**
   - Cache concatenated spec content to avoid re-reading files on every render
   - Use Livewire's `#[Computed]` attribute for concatenation logic

### File System Interaction

- Read .agent-os directory structure using PHP `DirectoryIterator` or `Finder` facade
- Support reading from project root's `.agent-os` folder by default
- Default/index view displays concatenated `.agent-os/product/` folder contents
- Include project's `README.md` as a navigation item in sidebar
- Parse spec folder names to extract date and title:
  - Pattern: `YYYY-MM-DD-spec-name`
  - Extract date for chronological sorting
  - Extract title for display (convert kebab-case to Title Case)
- Support merging additional markdown directories via configuration:
  ```php
  'viewer' => [
      'paths' => [
          '.agent-os' => 'Agent OS Documentation',
          'docs' => 'User Documentation', // additional directory
      ],
  ]
  ```
- Cache file tree structure in Livewire component state to avoid re-scanning on every render
- Handle missing .agent-os directory gracefully with helpful error message
- Display additional directories as separate sections in the sidebar

### Markdown Parsing

- Use `league/commonmark` package (already in Laravel) for markdown to HTML conversion
- Enable GitHub Flavored Markdown extension for task lists, tables, and syntax highlighting
- Add custom renderer for Agent OS @ reference links (e.g., `@.agent-os/product/mission.md`) to convert to clickable internal links
- Syntax highlighting for code blocks using Highlight.js or Shiki (configured via Vite)

### Search Implementation

- Create search service class `AgentOsSearchService` in `src/Services/`
- Search algorithm:
  1. Recursively scan all `.md` files in `.agent-os/` directory and any additional configured paths
  2. Include README.md from project root in search
  3. Read file contents and perform case-insensitive string matching
  4. Extract context snippet (150 characters before/after match)
  5. Return array of results with file path, line number, snippet, and source directory
- Implement search result ranking by number of matches per file
- Support multi-word phrase search with quote wrapping (e.g., `"database schema"`)
- Display which directory/section each result is from (e.g., "Agent OS Documentation" vs "User Documentation")

### Access Control

- Create `AgentOsViewerMiddleware` with configurable authorization logic
- Default behavior: allow access in local environment, require authentication in production
- Publish config file `agent-os-installer.php` with middleware customization options:
  ```php
  'viewer' => [
      'enabled' => env('AGENT_OS_VIEWER_ENABLED', true),
      'route_prefix' => env('AGENT_OS_ROUTE_PREFIX', 'agent-os'),
      'middleware' => ['web'], // customize per project
      'gate' => null, // optional gate name to check
      'paths' => [
          '.agent-os' => 'Agent OS Documentation',
          // Add additional markdown directories:
          // 'docs' => 'User Documentation',
      ],
      'default_view' => 'product', // 'product' shows concatenated product folder, 'readme' shows README.md
  ]
  ```
- Document how to override middleware in package README

### UI Components

- Use Flux UI base components only (no Pro components): `flux:input`, `flux:button`, `flux:heading`, `flux:text`
- Build custom layouts using Tailwind CSS classes for cards, badges, and sidebar navigation
- Package should NOT require `livewire/flux-pro` dependency - only `livewire/flux` (base package)
- Create three main Livewire components:
  1. `AgentOsViewer` - Main layout with sidebar and content area, handles spec concatenation logic
  2. `SidebarNavigation` - Flat list navigation with grouped sections (Product Docs, Specs, Additional Dirs, README)
  3. `SearchResults` - Display search results with highlighting
- Responsive layout: sidebar collapses to hamburger menu on mobile using Tailwind responsive classes
- Dark mode support using Tailwind's dark mode classes

**Navigation Implementation:**
- Sidebar uses simple flat list structure (no expandable trees)
- Specs are sorted in reverse chronological order (newest first)
- Active item highlighted with background color or border
- Each nav item is a clickable link that loads the appropriate view in main content area

### Performance Considerations

- Lazy load file contents (don't read all files into memory at once)
- Limit search results to first 50 matches to prevent timeout on large docs
- Add optional file content caching with configurable TTL (default: 5 minutes in dev, 60 minutes in production)
- Use Livewire's `#[Computed]` attribute for expensive operations

## Approach Options

### Option A: Single Livewire Component (Simple)

**Description:** Create one large `AgentOsViewer` component that handles navigation, content display, and search all in one class.

**Pros:**
- Faster initial development
- Less complexity in component communication
- Easier to understand for package consumers

**Cons:**
- Harder to maintain and test
- Component becomes bloated with multiple responsibilities
- Less reusable across different contexts

### Option B: Multiple Specialized Components (Modular) - Selected

**Description:** Break functionality into three focused Livewire components: `AgentOsViewer` (layout), `FileTree` (navigation), and `SearchResults` (search display).

**Pros:**
- Better separation of concerns
- Each component is testable in isolation
- Components can be reused or extended by package consumers
- Follows Laravel/Livewire best practices

**Cons:**
- More files to manage
- Component communication requires events or properties
- Slightly more complex initial setup

**Rationale:** Modular approach aligns with our code quality standards and makes the package more maintainable. The additional complexity is justified by improved testability and extensibility.

### Option C: Full SPA with Inertia.js

**Description:** Use Inertia.js for client-side routing and Vue/React for the interface.

**Pros:**
- Smoother navigation without full page reloads
- More sophisticated UI interactions possible

**Cons:**
- Introduces JavaScript framework dependency
- Breaks consistency with Livewire-first approach
- Overkill for documentation viewing
- Requires host app to have Inertia configured

**Rationale:** Rejected because it conflicts with our Livewire-first architecture and adds unnecessary complexity for a documentation viewer.

## External Dependencies

### league/commonmark

**Purpose:** Parse markdown files and convert to HTML

**Justification:** Already included in Laravel framework, no additional dependency. Provides robust markdown parsing with extensibility for custom renderers.

**Version:** ^2.4 (included in Laravel 11)

### livewire/flux (Base Package Only)

**Purpose:** UI components for interface elements (buttons, inputs, headings)

**Justification:** Provides accessible, well-designed base components. We will only use the free/base Flux UI components, NOT flux-pro. This keeps the package accessible to all users without requiring a Flux Pro license.

**Version:** ^1.0 (base Flux UI package)

**Important:** Package must NOT depend on `livewire/flux-pro`. Only use base Flux components available in the free package.

### No Additional Dependencies Required

The remaining functionality (file scanning, search, access control) can be implemented using native PHP and Laravel's built-in features. Custom UI elements like cards, badges, and navigation will be built with Tailwind CSS rather than Pro components.

## Integration Points

### Service Provider Registration

- Register routes with Route::group in `AgentOsInstallerServiceProvider`
- Publish views, config, and assets using standard Laravel publishing
- Auto-discover Livewire components using Livewire's automatic discovery

### Customization Hooks

- Allow host apps to override views by publishing to `resources/views/vendor/agent-os-installer/`
- Provide config file for middleware, route prefix, and feature flags
- Document event hooks for tracking viewer usage (optional analytics)

### Existing Agent OS Integration

- Detect and parse all standard Agent OS files:
  - `.agent-os/product/mission.md`
  - `.agent-os/product/roadmap.md`
  - `.agent-os/product/tech-stack.md`
  - `.agent-os/product/decisions.md`
  - `.agent-os/specs/*/spec.md`
  - `.agent-os/specs/*/sub-specs/*.md`
  - `.agent-os/specs/*/tasks.md`
- Render @ reference links as internal navigation (e.g., clicking `@.agent-os/product/mission.md` navigates to that file in the viewer)
