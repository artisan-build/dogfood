# Spec Tasks

These are the tasks to be completed for the spec detailed in @.agent-os/specs/2025-11-01-agent-os-web-viewer/spec.md

> Created: 2025-11-01
> Status: Ready for Implementation

## Tasks

- [ ] 1. Set up package structure and configuration
  - [ ] 1.1 Write tests for config file structure and default values
  - [ ] 1.2 Create config file with viewer settings (enabled, route_prefix, middleware, paths, default_view)
  - [ ] 1.3 Update service provider to register routes with configurable prefix
  - [ ] 1.4 Add route registration with environment variable support (AGENT_OS_ROUTE_PREFIX, AGENT_OS_VIEWER_ENABLED)
  - [ ] 1.5 Verify all tests pass

- [ ] 2. Implement file system scanning and navigation structure
  - [ ] 2.1 Write tests for directory scanner service
  - [ ] 2.2 Create service to scan .agent-os directory and additional configured paths
  - [ ] 2.3 Implement spec folder name parsing (extract date and title from YYYY-MM-DD-spec-name format)
  - [ ] 2.4 Implement chronological sorting (reverse order, newest first)
  - [ ] 2.5 Implement title formatting (kebab-case to Title Case conversion)
  - [ ] 2.6 Handle README.md inclusion and missing directories gracefully
  - [ ] 2.7 Verify all tests pass

- [ ] 3. Build Product folder concatenation logic
  - [ ] 3.1 Write tests for Product folder concatenation service
  - [ ] 3.2 Create service to read and concatenate .agent-os/product/ files (mission, roadmap, tech-stack, decisions)
  - [ ] 3.3 Implement section header generation from filenames
  - [ ] 3.4 Strip top-level headings from non-first files
  - [ ] 3.5 Add horizontal rule separators between sections
  - [ ] 3.6 Implement caching with Livewire #[Computed] attribute
  - [ ] 3.7 Verify all tests pass

- [ ] 4. Build Spec concatenation logic
  - [ ] 4.1 Write tests for Spec concatenation service
  - [ ] 4.2 Create service to read and concatenate spec files (spec.md + sub-specs/*.md + tasks.md)
  - [ ] 4.3 Implement correct ordering (spec.md first, sub-specs alphabetically, tasks.md last)
  - [ ] 4.4 Generate section headers from sub-spec filenames
  - [ ] 4.5 Strip top-level headings and add separators
  - [ ] 4.6 Handle missing tasks.md gracefully
  - [ ] 4.7 Implement caching for concatenated spec content
  - [ ] 4.8 Verify all tests pass

- [ ] 5. Implement markdown parsing and rendering
  - [ ] 5.1 Write tests for markdown parser with CommonMark
  - [ ] 5.2 Configure CommonMark with GitHub Flavored Markdown extension
  - [ ] 5.3 Create custom renderer for Agent OS @ reference links
  - [ ] 5.4 Convert @ links to internal navigation (e.g., @.agent-os/product/mission.md)
  - [ ] 5.5 Add syntax highlighting configuration for code blocks
  - [ ] 5.6 Test table rendering, task lists, and HTML escaping
  - [ ] 5.7 Verify all tests pass

- [ ] 6. Build search functionality
  - [ ] 6.1 Write tests for AgentOsSearchService
  - [ ] 6.2 Create AgentOsSearchService class in src/Services/
  - [ ] 6.3 Implement recursive file scanning across .agent-os and configured paths
  - [ ] 6.4 Implement case-insensitive string matching
  - [ ] 6.5 Add context snippet extraction (150 chars before/after match)
  - [ ] 6.6 Implement multi-word phrase search with quote support
  - [ ] 6.7 Add result ranking by match count and limit to 50 results
  - [ ] 6.8 Include source directory in results for display
  - [ ] 6.9 Verify all tests pass

- [ ] 7. Create SidebarNavigation Livewire component
  - [ ] 7.1 Write tests for SidebarNavigation component
  - [ ] 7.2 Create SidebarNavigation component class in src/Livewire/
  - [ ] 7.3 Build navigation structure (Product, Specs, Additional Dirs, README)
  - [ ] 7.4 Implement spec sorting (reverse chronological)
  - [ ] 7.5 Create component view with Flux UI base components
  - [ ] 7.6 Style with Tailwind CSS for sidebar layout
  - [ ] 7.7 Add active item highlighting
  - [ ] 7.8 Implement mobile responsive collapsing
  - [ ] 7.9 Verify all tests pass

- [ ] 8. Create AgentOsViewer Livewire component
  - [ ] 8.1 Write tests for AgentOsViewer component
  - [ ] 8.2 Create AgentOsViewer component class in src/Livewire/
  - [ ] 8.3 Implement main layout with sidebar and content area
  - [ ] 8.4 Add route handling for index (Product view), specs, and README
  - [ ] 8.5 Integrate Product concatenation logic
  - [ ] 8.6 Integrate Spec concatenation logic
  - [ ] 8.7 Implement single file view for README and other docs
  - [ ] 8.8 Create component view with proper layout structure
  - [ ] 8.9 Add dark mode support with Tailwind classes
  - [ ] 8.10 Verify all tests pass

- [ ] 9. Create SearchResults Livewire component
  - [ ] 9.1 Write tests for SearchResults component
  - [ ] 9.2 Create SearchResults component class in src/Livewire/
  - [ ] 9.3 Integrate AgentOsSearchService
  - [ ] 9.4 Implement search input with Flux UI components
  - [ ] 9.5 Create results display with highlighting
  - [ ] 9.6 Show file path, context snippet, and source directory
  - [ ] 9.7 Add loading state and "no results" message
  - [ ] 9.8 Make results clickable to navigate to file
  - [ ] 9.9 Verify all tests pass

- [ ] 10. Implement access control and middleware
  - [ ] 10.1 Write tests for AgentOsViewerMiddleware
  - [ ] 10.2 Create AgentOsViewerMiddleware class
  - [ ] 10.3 Implement default behavior (allow local, require auth in production)
  - [ ] 10.4 Add customizable gate checking
  - [ ] 10.5 Apply middleware to routes based on config
  - [ ] 10.6 Document middleware customization in package README
  - [ ] 10.7 Verify all tests pass

- [ ] 11. Add views and styling
  - [ ] 11.1 Create Blade view for AgentOsViewer component layout
  - [ ] 11.2 Create Blade view for SidebarNavigation component
  - [ ] 11.3 Create Blade view for SearchResults component
  - [ ] 11.4 Build custom card/badge components with Tailwind CSS (no Pro components)
  - [ ] 11.5 Ensure responsive layout works on mobile
  - [ ] 11.6 Add syntax highlighting assets (Highlight.js or Shiki via Vite)
  - [ ] 11.7 Test dark mode styling
  - [ ] 11.8 Verify all tests pass

- [ ] 12. Integration testing and polish
  - [ ] 12.1 Write feature tests for complete workflows (browsing, search, navigation)
  - [ ] 12.2 Test Product folder view displays correctly on index route
  - [ ] 12.3 Test spec unified view with all concatenated sections
  - [ ] 12.4 Test navigation between Product, specs, and README
  - [ ] 12.5 Test search across all documentation types
  - [ ] 12.6 Test route prefix customization via environment variable
  - [ ] 12.7 Test additional directories configuration
  - [ ] 12.8 Test access control with different middleware configurations
  - [ ] 12.9 Verify all tests pass

- [ ] 13. Documentation and package publishing
  - [ ] 13.1 Update package README with installation instructions
  - [ ] 13.2 Document configuration options and examples
  - [ ] 13.3 Document middleware customization
  - [ ] 13.4 Add usage examples for route prefix and additional directories
  - [ ] 13.5 Document @ reference link syntax
  - [ ] 13.6 Add screenshots or GIFs of the interface
  - [ ] 13.7 Update CHANGELOG with new features
  - [ ] 13.8 Verify composer ready passes
